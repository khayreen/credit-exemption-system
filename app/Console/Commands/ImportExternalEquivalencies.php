<?php

namespace App\Console\Commands;

use App\Models\CourseEquivalency;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\XLSX\Reader;

class ImportExternalEquivalencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:external-equivalencies
                            {file : Path to the Excel file (.xlsx)}
                            {--dry-run : Run without actually importing data}
                            {--skip-header=1 : Number of header rows to skip (default: 1)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import course equivalencies from external institutions spreadsheet';

    /**
     * Target degree programs for UiTM Jasin
     */
    protected array $targetPrograms = ['CDCS251', 'CDCS255', 'CDCS266', 'CDCS230', 'CDCS253'];

    /**
     * Program code mapping from spreadsheet format to database format
     */
    protected array $programMapping = [
        'CS251' => 'CDCS251',
        'CS255' => 'CDCS255',
        'CS266' => 'CDCS266',
        'CS230' => 'CDCS230',
        'CS253' => 'CDCS253',
    ];

    /**
     * Statistics tracking
     */
    protected int $totalRows = 0;
    protected int $matchedRows = 0;
    protected int $importedRows = 0;
    protected int $duplicateRows = 0;
    protected int $skippedRows = 0;
    protected int $tidakLulusRows = 0;
    protected int $errorRows = 0;

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filePath = $this->argument('file');
        $dryRun = $this->option('dry-run');
        $skipHeader = (int) $this->option('skip-header');

        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $this->info("Processing file: {$filePath}");
        if ($dryRun) {
            $this->warn("DRY RUN MODE - No data will be imported");
        }

        // Get a system user for approved_by_user_id (or create one)
        $approver = $this->getSystemApprover();
        if (!$approver) {
            $this->error("Could not find or create a system user for approvals");
            return 1;
        }

        try {
            $reader = new Reader();
            $reader->open($filePath);

            foreach ($reader->getSheetIterator() as $sheet) {
                $sheetName = $sheet->getName();

                // Only process sheets with 3-letter names (course code prefixes like CSC, MAT, ICT)
                if (!$this->isValidSheetName($sheetName)) {
                    $this->line("  Skipping sheet: {$sheetName} (not a course code prefix)");
                    continue;
                }

                $this->info("Processing sheet: {$sheetName}");
                $rowNumber = 0;

                foreach ($sheet->getRowIterator() as $row) {
                    $rowNumber++;

                    // Skip header rows
                    if ($rowNumber <= $skipHeader) {
                        continue;
                    }

                    $cells = $row->getCells();
                    $this->totalRows++;

                    // Parse row data
                    $rowData = $this->parseRow($cells);

                    if (!$rowData) {
                        $this->skippedRows++;
                        continue;
                    }

                    // Check if program matches target programs
                    $matchedPrograms = $this->getMatchedPrograms($rowData['program_raw']);

                    if (empty($matchedPrograms)) {
                        $this->skippedRows++;
                        continue;
                    }

                    // Track LULUS vs TIDAK LULUS counts
                    if (!$rowData['is_approved']) {
                        $this->tidakLulusRows++;
                    }

                    $this->matchedRows++;

                    // Import for each matched program
                    foreach ($matchedPrograms as $programCode) {
                        $result = $this->importEquivalency($rowData, $programCode, $approver->id, $dryRun);

                        if ($result === 'imported') {
                            $this->importedRows++;
                        } elseif ($result === 'duplicate') {
                            $this->duplicateRows++;
                        } elseif ($result === 'error') {
                            $this->errorRows++;
                        }
                    }
                }
            }

            $reader->close();

        } catch (\Exception $e) {
            $this->error("Error processing file: " . $e->getMessage());
            return 1;
        }

        // Display summary
        $this->displaySummary($dryRun);

        return 0;
    }

    /**
     * Check if sheet name is a valid course code prefix (3 uppercase letters)
     */
    protected function isValidSheetName(string $name): bool
    {
        return preg_match('/^[A-Z]{3}$/i', trim($name)) === 1;
    }

    /**
     * Parse a row of data from the spreadsheet
     */
    protected function parseRow(array $cells): ?array
    {
        // Ensure we have enough columns
        if (count($cells) < 9) {
            return null;
        }

        $degreeCourseCode = $this->getCellValue($cells, 0);
        $degreeCourseName = $this->getCellValue($cells, 1);
        $degreeCreditHour = $this->getCellValue($cells, 2);
        $programRaw = $this->getCellValue($cells, 3);
        $diplomaCourseCode = $this->getCellValue($cells, 4);
        $diplomaCourseName = $this->getCellValue($cells, 5);
        $diplomaCreditHour = $this->getCellValue($cells, 6);
        $institution = $this->getCellValue($cells, 7);
        $matchPercentage = $this->getCellValue($cells, 8);
        $passStatus = $this->getCellValue($cells, 9); // Column J: LULUS/TIDAK LULUS

        // Skip rows with missing essential data
        if (empty($diplomaCourseCode) || empty($degreeCourseCode) || empty($institution)) {
            return null;
        }

        // Parse and validate credit hours
        $degreeCreditHour = $this->parseNumber($degreeCreditHour, 3);
        $diplomaCreditHour = $this->parseNumber($diplomaCreditHour, 3);

        // Parse match percentage (remove % sign if present)
        $matchPercentage = $this->parsePercentage($matchPercentage);

        // Determine if course is approved (LULUS = pass, TIDAK LULUS = fail)
        $isApproved = $this->parsePassStatus($passStatus);

        return [
            'degree_course_code' => strtoupper(trim($degreeCourseCode)),
            'degree_course_name' => trim($degreeCourseName),
            'degree_credit_hour' => $degreeCreditHour,
            'program_raw' => trim($programRaw),
            'diploma_course_code' => strtoupper(trim($diplomaCourseCode)),
            'diploma_course_name' => trim($diplomaCourseName),
            'diploma_credit_hour' => $diplomaCreditHour,
            'diploma_institution' => $this->normalizeInstitution(trim($institution)),
            'match_percentage' => $matchPercentage,
            'pass_status' => $passStatus,
            'is_approved' => $isApproved,
        ];
    }

    /**
     * Parse pass status (LULUS/TIDAK LULUS)
     */
    protected function parsePassStatus(string $status): bool
    {
        $status = strtoupper(trim($status));

        // LULUS = Pass, can get exemption
        // TIDAK LULUS = Fail, cannot get exemption
        if (str_contains($status, 'TIDAK')) {
            return false;
        }

        if (str_contains($status, 'LULUS')) {
            return true;
        }

        // Default: check if match percentage >= 80% would be handled elsewhere
        return true;
    }

    /**
     * Get cell value safely
     */
    protected function getCellValue(array $cells, int $index): string
    {
        if (!isset($cells[$index])) {
            return '';
        }

        $value = $cells[$index]->getValue();

        return is_null($value) ? '' : (string) $value;
    }

    /**
     * Parse a number value with default fallback
     */
    protected function parseNumber($value, $default = 0): int
    {
        if (is_numeric($value)) {
            return (int) $value;
        }

        $cleaned = preg_replace('/[^0-9.]/', '', $value);
        return $cleaned !== '' ? (int) $cleaned : $default;
    }

    /**
     * Parse percentage value
     */
    protected function parsePercentage($value): float
    {
        if (is_numeric($value)) {
            // If it's already a decimal like 0.85, convert to percentage
            if ($value <= 1) {
                return (float) $value * 100;
            }
            return (float) $value;
        }

        // Remove % sign and parse
        $cleaned = preg_replace('/[^0-9.]/', '', $value);
        return $cleaned !== '' ? (float) $cleaned : 50.0;
    }

    /**
     * Normalize institution name
     */
    protected function normalizeInstitution(string $institution): string
    {
        // Remove extra whitespace
        $institution = preg_replace('/\s+/', ' ', $institution);

        // Common abbreviation expansions
        $mappings = [
            'UTM' => 'Universiti Teknologi Malaysia (UTM)',
            'UiTM' => 'Universiti Teknologi MARA (UiTM)',
            'MMU' => 'Multimedia University (MMU)',
            'GMI' => 'German Malaysia Institute (GMI)',
            'UPSI' => 'Universiti Pendidikan Sultan Idris (UPSI)',
        ];

        // If institution is just an abbreviation, expand it
        if (isset($mappings[$institution])) {
            return $mappings[$institution];
        }

        return $institution;
    }

    /**
     * Get matched programs from raw program string
     */
    protected function getMatchedPrograms(string $programRaw): array
    {
        $matched = [];

        // Handle formats like "CS245/246" or "CS251" or "CS245, CS246"
        // Split by / or ,
        $parts = preg_split('/[\/,]/', $programRaw);

        foreach ($parts as $part) {
            $part = trim($part);

            // Try to extract program code
            // Handle "CS251", "251", "CDCS251"
            if (preg_match('/(?:CD)?CS?(\d{3})/', $part, $matches)) {
                $shortCode = 'CS' . $matches[1];

                if (isset($this->programMapping[$shortCode])) {
                    $matched[] = $this->programMapping[$shortCode];
                }
            }
        }

        return array_unique($matched);
    }

    /**
     * Import a single equivalency record
     */
    protected function importEquivalency(array $rowData, string $programCode, string $approverId, bool $dryRun): string
    {
        // Check for existing duplicate
        $exists = CourseEquivalency::where('diploma_course_code', $rowData['diploma_course_code'])
            ->where('degree_course_code', $rowData['degree_course_code'])
            ->where('program_code', $programCode)
            ->where('diploma_institution', $rowData['diploma_institution'])
            ->exists();

        if ($exists) {
            return 'duplicate';
        }

        if ($dryRun) {
            $matchPct = $rowData['match_percentage'];
            $isEligible = $rowData['is_approved'];
            $status = $isEligible ? 'LULUS' : 'TIDAK LULUS';
            $statusColor = $isEligible ? 'green' : 'red';

            // Show detailed output with course names and credit hours
            $diplomaInfo = "{$rowData['diploma_course_code']} - {$rowData['diploma_course_name']} ({$rowData['diploma_credit_hour']} cr)";
            $degreeInfo = "{$rowData['degree_course_code']} - {$rowData['degree_course_name']} ({$rowData['degree_credit_hour']} cr)";
            $institutionShort = strlen($rowData['diploma_institution']) > 30
                ? substr($rowData['diploma_institution'], 0, 27) . '...'
                : $rowData['diploma_institution'];

            $this->line("  <fg={$statusColor}>[{$status}]</> <fg=yellow>{$diplomaInfo}</> ({$institutionShort})");
            $this->line("           -> <fg=white>{$degreeInfo}</> ({$programCode}) [<fg=cyan>{$matchPct}%</>]");
            return 'imported';
        }

        try {
            CourseEquivalency::create([
                'diploma_course_code' => $rowData['diploma_course_code'],
                'diploma_course_name' => $rowData['diploma_course_name'],
                'diploma_credit_hour' => $rowData['diploma_credit_hour'],
                'diploma_institution' => $rowData['diploma_institution'],
                'degree_course_code' => $rowData['degree_course_code'],
                'degree_course_name' => $rowData['degree_course_name'],
                'degree_credit_hour' => $rowData['degree_credit_hour'],
                'program_code' => $programCode,
                'match_percentage' => $rowData['match_percentage'],
                'is_eligible' => $rowData['is_approved'],
                'approved_by_user_id' => $approverId,
                'notes' => "Imported from external equivalencies spreadsheet",
                'source' => 'imported',
            ]);

            return 'imported';
        } catch (\Exception $e) {
            $this->warn("  Error importing row: " . $e->getMessage());
            return 'error';
        }
    }

    /**
     * Get or create a system user for approvals
     */
    protected function getSystemApprover(): ?User
    {
        // Try to find an admin user
        $admin = User::where('role', 'admin')
            ->orWhere('email', 'admin@uitm.edu.my')
            ->first();

        if ($admin) {
            return $admin;
        }

        // Try to find any resource person
        $resourcePerson = User::where('role', 'resource_person')->first();

        if ($resourcePerson) {
            return $resourcePerson;
        }

        // Return any user as fallback
        return User::first();
    }

    /**
     * Display import summary
     */
    protected function displaySummary(bool $dryRun): void
    {
        $this->newLine();
        $this->info("========== IMPORT SUMMARY ==========");

        if ($dryRun) {
            $this->warn("(DRY RUN - No data was actually imported)");
        }

        $lulusCount = $this->matchedRows - $this->tidakLulusRows;

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total rows processed', $this->totalRows],
                ['Rows matching target programs', $this->matchedRows],
                ['  - LULUS (eligible for exemption)', $lulusCount],
                ['  - TIDAK LULUS (not eligible)', $this->tidakLulusRows],
                ['Rows skipped (no program match/invalid)', $this->skippedRows],
                ['Duplicates found', $this->duplicateRows],
                ['Errors', $this->errorRows],
                [$dryRun ? 'Would import' : 'Successfully imported', $this->importedRows],
            ]
        );

        $this->newLine();
        $this->info("Target programs: " . implode(', ', $this->targetPrograms));
        $this->info("Both LULUS and TIDAK LULUS records are imported.");
        $this->info("  - LULUS (is_eligible=true): Student CAN get exemption");
        $this->info("  - TIDAK LULUS (is_eligible=false): Student CANNOT get exemption");
    }
}
