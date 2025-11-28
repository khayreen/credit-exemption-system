<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Institution;

class ScrapeMqaInstitutions extends Command
{
    protected $signature = 'scrape:institutions';
    protected $description = 'Scrape institution data from MQA website';

    public function handle()
    {
        $urls = [
            'https://www2.mqa.gov.my/mqr/akrbyipta.cfm' => 'IPTA',
            'https://www2.mqa.gov.my/mqr/akrbyipts.cfm' => 'IPTS',
            'https://www2.mqa.gov.my/mqr/akrbyiptapoli.cfm' => 'Politeknik',
            'https://www2.mqa.gov.my/mqr/akrbykomuniti.cfm' => 'Kolej Komuniti',
            'https://www2.mqa.gov.my/mqr/akrbyiptsku.cfm' => 'Kolej Universiti',
            'https://www2.mqa.gov.my/mqr/akrbyiptskolej.cfm' => 'Kolej',
        ];

        $this->info('Starting to scrape institutions...');
        Institution::truncate();
        $totalFound = 0;
        $foundInstitutions = []; // Track found institutions to prevent duplicates

        foreach ($urls as $url => $type) {
            $response = Http::withoutVerifying()->get($url);
            
            if ($response->successful()) {
                $this->info("Successfully fetched: $url");
                $crawler = new Crawler($response->body());

                // FINAL ROBUST METHOD: Find the correct table by analyzing its headers
                $crawler->filter('table')->each(function (Crawler $tableNode) use ($type, &$totalFound, &$foundInstitutions) {
                    // Check if the first row of this table looks like our target header
                    $firstRowTds = $tableNode->filter('tr')->first()->filter('td');
                    if ($firstRowTds->count() > 1 && 
                        (str_contains($firstRowTds->eq(1)->text(), 'Nama Institusi') || 
                         str_contains($firstRowTds->eq(1)->text(), 'NAMA KOLEJ') ||
                         str_contains($firstRowTds->eq(1)->text(), 'NAMA'))) {
                        
                        // We found the correct table, now process its rows
                        $tableNode->filter('tr')->each(function ($node) use ($type, &$totalFound, &$foundInstitutions) {
                            $cellCount = $node->filter('td')->count();
                            if ($cellCount >= 2) {
                                $rawName = trim($node->filter('td')->eq(1)->text());
                                $state = $cellCount > 2 ? trim($node->filter('td')->eq(2)->text()) : '';

                                // Clean the name by removing "(Nama Lama : ...)" text
                                $cleanName = preg_replace('/\s*\(Nama Lama\s*:\s*[^)]+\)/', '', $rawName);
                                $cleanName = trim($cleanName);

                                if (!empty($cleanName) && 
                                    trim($cleanName) !== "" &&
                                    $cleanName !== "Nama Institusi" && 
                                    $cleanName !== "NAMA KOLEJ" &&
                                    $cleanName !== "NAMA" &&
                                    $cleanName !== "NAMA IPTA" &&
                                    $cleanName !== "NAMA IPTS" &&
                                    $cleanName !== "NAMA POLITEKNIK" &&
                                    !str_starts_with($cleanName, "Nama ") &&
                                    !str_starts_with($cleanName, "Universiti Teknologi MARA") &&
                                    !str_contains($cleanName, "UiTM") &&
                                    !str_contains($cleanName, "UITM") &&
                                    strlen($cleanName) > 3 &&
                                    strlen($cleanName) < 255 &&
                                    !str_contains($cleanName, 'Program dengan no. rujukan') &&
                                    !str_contains($cleanName, ': Sebahagian') &&
                                    !preg_match('/^\s+$/', $cleanName) &&
                                    !in_array($cleanName, $foundInstitutions)) { // Check for duplicates
                                    
                                    $foundInstitutions[] = $cleanName; // Add to found list
                                    Institution::create([
                                        'name' => $cleanName,
                                        'state' => $state,
                                        'type' => $type,
                                    ]);
                                    $this->line("  -> Found: " . $cleanName);
                                    $totalFound++;
                                }
                            }
                        });
                    }
                });
            } else {
                $this->error("Failed to fetch URL: $url (Status: " . $response->status() . ")");
            }
        }
        $this->info("Scraping completed! Found and saved $totalFound institutions.");
    }
}
