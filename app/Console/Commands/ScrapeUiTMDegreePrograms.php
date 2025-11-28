<?php

namespace App\Console\Commands;

use App\Models\Faculty;
use App\Models\Program;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class ScrapeUiTMDegreePrograms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:uitm-degree-programs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape UiTM degree programs and faculties from official website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting UiTM degree programs scraping...');
        
        // URL from the image you provided (online.uitm.edu.my/degree.cfm)
        $url = 'https://online.uitm.edu.my/degree.cfm';
        
        try {
            $response = Http::withoutVerifying()->get($url);
            
            if (!$response->successful()) {
                $this->error("Failed to fetch data from {$url}");
                return 1;
            }
            
            $crawler = new Crawler($response->body());
            
            // Look for the table containing the program data
            $tableFound = false;
            $facultyCount = 0;
            $programCount = 0;
            
            $crawler->filter('table')->each(function (Crawler $tableNode) use (&$tableFound, &$facultyCount, &$programCount) {
                // Check if this table contains faculty/program data
                $headerRow = $tableNode->filter('tr')->first();
                if ($headerRow->filter('td, th')->count() > 0) {
                    $headerText = $headerRow->text();
                    
                    // Look for table with "KOD PROGRAM" or "NAMA PROGRAM" headers
                    if (str_contains($headerText, 'KOD PROGRAM') || str_contains($headerText, 'NAMA PROGRAM')) {
                        $tableFound = true;
                        $this->info('Found program table, processing...');
                        
                        // Process each row in the table
                        $tableNode->filter('tr')->each(function (Crawler $rowNode, $index) use (&$facultyCount, &$programCount) {
                            $cells = $rowNode->filter('td');
                            
                            // Check for faculty header row (may span entire row)
                            $rowText = trim($rowNode->text());
                            
                            // Look for faculty patterns in the full row text (FAKULTI, AKADEMI, KOLEJ)
                            if (preg_match('/^\d+\.\s*(FAKULTI|AKADEMI|KOLEJ)\s+(.+?)\s*\d*$/i', $rowText, $matches)) {
                                $institutionType = strtoupper($matches[1]);
                                $institutionName = $institutionType . ' ' . trim($matches[2]);
                                
                                // Clean up any trailing numbers or extra content
                                $institutionName = preg_replace('/\s+\d+\s*$/', '', $institutionName);
                                $institutionName = preg_replace('/\s+/', ' ', $institutionName);
                                
                                // Generate code from name
                                $words = explode(' ', str_replace($institutionType . ' ', '', $institutionName));
                                $code = '';
                                foreach ($words as $word) {
                                    if (!empty($word) && strlen($word) > 2) {
                                        $code .= strtoupper(substr($word, 0, 1));
                                    }
                                }
                                if (strlen($code) < 2) {
                                    $code = strtoupper(substr(md5($institutionName), 0, 3));
                                }
                                
                                if (!empty($institutionName) && strlen($institutionName) > 8) {
                                    // Create or update faculty (treating AKADEMI and KOLEJ as faculties too)
                                    Faculty::updateOrCreate(
                                        ['name' => $institutionName],
                                        [
                                            'code' => $code,
                                            'description' => $institutionName,
                                            'is_active' => true
                                        ]
                                    );
                                    
                                    $facultyCount++;
                                    $this->line("Faculty: {$institutionName} [{$code}]");
                                }
                            }
                            elseif ($cells->count() >= 2) {
                                $firstCell = trim($cells->eq(0)->text());
                                $secondCell = trim($cells->eq(1)->text());
                                
                                // Check if this is a program row (has program code)
                                if (preg_match('/^[A-Z]{2}\d{3}$/', $firstCell)) {
                                    $programCode = $firstCell;
                                    $programName = $secondCell;
                                    
                                    if (!empty($programName) && !str_contains($programName, 'NAMA PROGRAM')) {
                                        // Clean the program name
                                        $cleanedProgramName = preg_replace('/\s+/', ' ', trim($programName));
                                        
                                        // Create or update program
                                        Program::updateOrCreate(
                                            ['code' => $programCode],
                                            [
                                                'name' => $cleanedProgramName
                                            ]
                                        );
                                        
                                        $programCount++;
                                        $this->line("Program: {$programCode} - {$cleanedProgramName}");
                                    }
                                }
                            }
                        });
                    }
                }
            });
            
            if (!$tableFound) {
                $this->warn('Could not find the program table on the page. The page structure may have changed.');
                return 1;
            }
            
            $this->info("Scraping completed successfully!");
            $this->info("Faculties processed: {$facultyCount}");
            $this->info("Programs processed: {$programCount}");
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error occurred while scraping: " . $e->getMessage());
            return 1;
        }
    }
}