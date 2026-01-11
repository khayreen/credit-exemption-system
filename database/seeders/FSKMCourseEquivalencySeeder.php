<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FSKMCourseEquivalencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder imports ALL course equivalencies from the Excel file
     * /storage/app/Pengecualian_Kredit_FSKM.xlsx
     *
     * The file contains equivalencies from:
     * - Internal: UiTM diploma → UiTM degree
     * - External: Politeknik, UTM, KPMARA, and other institutions → UiTM degree
     *
     * Data is organized across multiple subject-based sheets (CSC, STA, ITT, ITS, MAT, etc.)
     */
    public function run(): void
    {
        $filePath = storage_path('app/Pengecualian_Kredit_FSKM.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("File not found: {$filePath}");
            return;
        }

        // Get a resource person user for approval
        $resourcePersonUser = User::where('role', 'resource_person')->first();
        if (!$resourcePersonUser) {
            $this->command->warn('No resource person found. Creating test resource person...');
            $resourcePersonUser = User::create([
                'name' => 'Resource Person',
                'email' => 'rp@test.com',
                'password' => bcrypt('password'),
                'role' => 'resource_person',
                'current_role' => 'resource_person',
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('Loading Excel file: Pengecualian_Kredit_FSKM.xlsx');
        $spreadsheet = IOFactory::load($filePath);

        // Sheets to process (skip metadata sheets)
        $sheetsToProcess = ['CSC', 'STA', 'ITT', 'ITS', 'MAT', 'ISP', 'ICT', 'QMT', 'Lain2', 'CTU'];

        $totalImported = 0;
        $totalSkipped = 0;
        $totalDuplicates = 0;

        foreach ($sheetsToProcess as $sheetName) {
            $this->command->info("Processing sheet: {$sheetName}");

            $worksheet = $spreadsheet->getSheetByName($sheetName);
            if (!$worksheet) {
                $this->command->warn("Sheet {$sheetName} not found. Skipping...");
                continue;
            }

            $highestRow = $worksheet->getHighestRow();

            // Start from row 6 (header is at row 5)
            $imported = 0;
            $skipped = 0;
            $duplicates = 0;

            for ($row = 6; $row <= $highestRow; $row++) {
                // Read data from columns
                // Columns A-C: Degree course (UiTM target course)
                $degreeCode = trim($worksheet->getCell('A' . $row)->getValue() ?? '');
                $degreeName = trim($worksheet->getCell('B' . $row)->getValue() ?? '');
                $degreeCreditHour = $worksheet->getCell('C' . $row)->getValue();

                // Column D: Program code
                $programCode = trim($worksheet->getCell('D' . $row)->getValue() ?? '');

                // Columns E-H: Diploma course (source - internal UiTM or external)
                $diplomaCode = trim($worksheet->getCell('E' . $row)->getValue() ?? '');
                $diplomaName = trim($worksheet->getCell('F' . $row)->getValue() ?? '');
                $diplomaCreditHour = $worksheet->getCell('G' . $row)->getValue();
                $institution = trim($worksheet->getCell('H' . $row)->getValue() ?? '');

                // Columns I-J: Equivalency info
                $matchPercentageDecimal = $worksheet->getCell('I' . $row)->getValue();
                $eligibilityStatus = trim($worksheet->getCell('J' . $row)->getValue() ?? '');

                // Skip if essential fields are empty
                if (empty($degreeCode) || empty($diplomaCode) || empty($institution)) {
                    $skipped++;
                    continue;
                }

                // Convert match percentage from decimal to percentage (0.6667 -> 66.67)
                $matchPercentage = is_numeric($matchPercentageDecimal)
                    ? round($matchPercentageDecimal * 100, 2)
                    : 0;

                // Determine eligibility based on status
                $isEligible = (stripos($eligibilityStatus, 'LULUS') !== false && stripos($eligibilityStatus, 'TIDAK') === false);

                // Check if this equivalency already exists
                $exists = DB::table('course_equivalencies')
                    ->where('diploma_course_code', $diplomaCode)
                    ->where('degree_course_code', $degreeCode)
                    ->where('diploma_institution', $institution)
                    ->where('program_code', $programCode)
                    ->exists();

                if ($exists) {
                    $duplicates++;
                    continue;
                }

                // Insert the record
                try {
                    DB::table('course_equivalencies')->insert([
                        'id' => (string) Str::uuid(),
                        'diploma_course_code' => $diplomaCode,
                        'diploma_course_name' => $diplomaName ?: 'N/A',
                        'diploma_credit_hour' => is_numeric($diplomaCreditHour) ? (int) $diplomaCreditHour : 3,
                        'diploma_institution' => $institution,
                        'degree_course_code' => $degreeCode,
                        'degree_course_name' => $degreeName ?: 'N/A',
                        'degree_credit_hour' => is_numeric($degreeCreditHour) ? (int) $degreeCreditHour : 3,
                        'program_code' => $programCode ?: null,
                        'match_percentage' => $matchPercentage,
                        'is_eligible' => $isEligible,
                        'is_published' => true, // Publish all imported equivalencies
                        'approved_by_user_id' => $resourcePersonUser->id,
                        'source' => 'imported',
                        'notes' => "Imported from {$sheetName} sheet (FSKM data)",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $this->command->error("Error importing row {$row} from {$sheetName}: " . $e->getMessage());
                    $skipped++;
                }
            }

            $this->command->info("  ✓ {$sheetName}: {$imported} imported, {$duplicates} duplicates, {$skipped} skipped");

            $totalImported += $imported;
            $totalDuplicates += $duplicates;
            $totalSkipped += $skipped;
        }

        $this->command->newLine();
        $this->command->info("===========================================");
        $this->command->info("FSKM Course Equivalency Import Summary:");
        $this->command->info("  Total imported: {$totalImported}");
        $this->command->info("  Total duplicates: {$totalDuplicates}");
        $this->command->info("  Total skipped: {$totalSkipped}");
        $this->command->info("===========================================");
    }
}
