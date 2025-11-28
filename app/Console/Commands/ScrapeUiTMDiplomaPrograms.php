<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Program;

class ScrapeUiTMDiplomaPrograms extends Command
{
    protected $signature = 'scrape:uitm-diploma-programs';
    protected $description = 'Scrape UiTM diploma programs from official UiTM website';

    public function handle()
    {
        $url = 'https://online.uitm.edu.my/diploma.cfm';
        
        $this->info('Starting to scrape UiTM diploma programs...');
        
        $response = Http::withoutVerifying()->get($url);
        
        if (!$response->successful()) {
            $this->error("Failed to fetch URL: $url (Status: " . $response->status() . ")");
            return 1;
        }

        $this->info("Successfully fetched: $url");
        $crawler = new Crawler($response->body());
        
        $diplomaPrograms = [];
        $totalFound = 0;

        // Find tables that contain program data
        $crawler->filter('table')->each(function (Crawler $tableNode) use (&$diplomaPrograms, &$totalFound) {
            // Look for tables with headers that include "KOD PROGRAM" and "NAMA PROGRAM"
            $headerFound = false;
            $tableNode->filter('tr')->first()->filter('td, th')->each(function (Crawler $headerCell) use (&$headerFound) {
                $headerText = trim($headerCell->text());
                if (str_contains($headerText, 'KOD PROGRAM') || str_contains($headerText, 'NAMA PROGRAM')) {
                    $headerFound = true;
                }
            });

            if ($headerFound) {
                $this->info('Found program table, extracting data...');
                
                // Process each row in the table
                $tableNode->filter('tr')->each(function (Crawler $rowNode) use (&$diplomaPrograms, &$totalFound) {
                    $cells = $rowNode->filter('td');
                    
                    if ($cells->count() >= 5) { // Ensure we have enough columns
                        $bil = trim($cells->eq(0)->text());
                        $fakulti = trim($cells->eq(1)->text());
                        $kodProgram = trim($cells->eq(2)->text());
                        $namaProgram = trim($cells->eq(3)->text());
                        $lepasan = trim($cells->eq(4)->text());

                        // Filter for diploma programs only (skip headers and asasi programs)
                        if (!empty($kodProgram) && 
                            !str_contains($kodProgram, 'KOD PROGRAM') &&
                            !str_contains($namaProgram, 'NAMA PROGRAM') &&
                            (str_contains(strtolower($namaProgram), 'diploma') || 
                             preg_match('/^[A-Z]{2}\d{3}$/', $kodProgram))) {
                            
                            $diplomaPrograms[] = [
                                'code' => $kodProgram,
                                'name' => $namaProgram,
                            ];
                            
                            $this->line("  -> Found: $kodProgram - $namaProgram");
                            $totalFound++;
                        }
                    }
                });
            }
        });

        // Remove duplicates based on program code
        $uniquePrograms = collect($diplomaPrograms)->unique('code')->values()->all();
        
        $this->info('Saving UiTM diploma programs to database...');
        
        // Update or create programs
        foreach ($uniquePrograms as $programData) {
            Program::updateOrCreate(
                ['code' => $programData['code']],
                $programData
            );
        }

        $uniqueCount = count($uniquePrograms);
        $this->info("Scraping completed! Found $totalFound total programs, saved $uniqueCount unique diploma programs.");
        
        return 0;
    }
}