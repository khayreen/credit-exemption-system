<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Campus;

class ScrapeUiTMCampuses extends Command
{
    protected $signature = 'scrape:uitm-campuses';
    protected $description = 'Scrape UiTM campus data from MQA website (entries 28-69)';

    public function handle()
    {
        $url = 'https://www2.mqa.gov.my/mqr/akrbyipta.cfm';
        
        $this->info('Starting to scrape UiTM campuses from MQA...');
        
        $response = Http::withoutVerifying()->get($url);
        
        if (!$response->successful()) {
            $this->error("Failed to fetch URL: $url (Status: " . $response->status() . ")");
            return 1;
        }

        $this->info("Successfully fetched: $url");
        $crawler = new Crawler($response->body());
        
        $totalFound = 0;
        $uitmCampuses = [];

        // Find the correct table by analyzing its headers
        $crawler->filter('table')->each(function (Crawler $tableNode) use (&$totalFound, &$uitmCampuses) {
            // Check if the first row of this table looks like our target header
            $firstRowTds = $tableNode->filter('tr')->first()->filter('td');
            if ($firstRowTds->count() > 2 && str_contains($firstRowTds->eq(1)->text(), 'Nama')) {
                
                $rowNumber = 0;
                // Process table rows
                $tableNode->filter('tr')->each(function ($node) use (&$totalFound, &$uitmCampuses, &$rowNumber) {
                    $rowNumber++;
                    
                    // We want entries 28-69 that start with "Universiti Teknologi MARA"
                    if ($rowNumber >= 28 && $rowNumber <= 69 && $node->filter('td')->count() > 2) {
                        $name = trim($node->filter('td')->eq(1)->text());
                        $state = trim($node->filter('td')->eq(2)->text());

                        if (str_starts_with($name, 'Universiti Teknologi MARA')) {
                            // Extract campus name from the full institution name
                            // Format: "Universiti Teknologi MARA (UiTM) Cawangan [Campus] Kampus [Specific Campus]"
                            // or: "Universiti Teknologi MARA (UiTM) [Campus]"
                            
                            $campusName = $this->extractCampusName($name);
                            
                            if ($campusName) {
                                $campusCode = $this->generateCampusCode($campusName, $state);
                                $displayName = 'UiTM ' . $campusName;
                                
                                $uitmCampuses[] = [
                                    'code' => $campusCode,
                                    'name' => $displayName,
                                    'state' => $state,
                                    'address' => null,
                                    'is_main_campus' => $campusName === 'Shah Alam',
                                ];
                                
                                $this->line("  -> Found: $displayName ($state) - Code: $campusCode");
                                $totalFound++;
                            }
                        }
                    }
                });
            }
        });

        // Save to database
        $this->info('Saving UiTM campuses to database...');
        
        foreach ($uitmCampuses as $campusData) {
            Campus::updateOrCreate(
                ['code' => $campusData['code']],
                $campusData
            );
        }

        $this->info("Scraping completed! Found and saved $totalFound UiTM campuses.");
        return 0;
    }

    private function extractCampusName($fullName)
    {
        // Remove "Universiti Teknologi MARA (UiTM)" prefix
        $name = str_replace('Universiti Teknologi MARA (UiTM)', '', $fullName);
        $name = trim($name);
        
        // Handle different naming patterns
        if (str_contains($name, 'Cawangan')) {
            // Pattern: "Cawangan [Campus] Kampus [Specific]"
            if (preg_match('/Cawangan\s+(.+?)(?:\s+Kampus\s+(.+))?$/', $name, $matches)) {
                $campus = trim($matches[1]);
                if (isset($matches[2]) && !empty(trim($matches[2]))) {
                    // Return specific campus if available (e.g., "Johor - Johor Bahru")
                    return $campus . ' - ' . trim($matches[2]);
                }
                return $campus;
            }
        } else {
            // Direct campus name - remove any state references
            $cleanName = trim($name);
            
            // For cases like "Shah Alam" or "Pulau Pinang - Balik Pulau"
            // Keep the full campus identifier but clean it up
            return $cleanName;
        }
        
        return null;
    }

    private function generateCampusCode($campusName, $state)
    {
        // Generate campus code based on campus name and state
        $words = explode(' ', $campusName);
        $code = '';
        
        foreach ($words as $word) {
            if (!in_array(strtolower($word), ['kampus', 'cawangan', '-', 'dan', 'the'])) {
                $code .= strtoupper(substr($word, 0, 1));
            }
        }
        
        // If code is too short, add state abbreviation
        if (strlen($code) < 2) {
            $stateCode = match($state) {
                'Selangor' => 'SEL',
                'Johor' => 'JHR', 
                'Kedah' => 'KDH',
                'Kelantan' => 'KTN',
                'Melaka' => 'MLK',
                'Negeri Sembilan' => 'NS',
                'Pahang' => 'PHG',
                'Perak' => 'PRK',
                'Perlis' => 'PLS',
                'Pulau Pinang' => 'PNG',
                'Sabah' => 'SBH',
                'Sarawak' => 'SWK',
                'Terengganu' => 'TRG',
                default => substr($state, 0, 3)
            };
            $code .= $stateCode;
        }
        
        return $code;
    }
}