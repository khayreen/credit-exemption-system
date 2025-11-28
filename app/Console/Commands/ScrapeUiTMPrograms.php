<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Program;

class ScrapeUiTMPrograms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:uitm-programs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape UiTM program data from MQA website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = 'https://www2.mqa.gov.my/mqr/english/eiptaKPListAA.cfm?IDAkrIPTS=248';

        $this->info('Starting to scrape UiTM programs...');
        Program::truncate(); // Clear the table before scraping
        $totalFound = 0;

        // Use withoutVerifying() to bypass potential SSL certificate issues
        $response = Http::withoutVerifying()->get($url);

        if ($response->successful()) {
            $this->info("Successfully fetched: $url");
            $crawler = new Crawler($response->body());

            // NEW ROBUST METHOD: Find the table by its header text
            $tableNode = $crawler->filter('td:contains("Name of Qualification")')->closest('table');

            if ($tableNode->count() > 0) {
                $tableNode->filter('tr')->each(function ($node) use (&$totalFound) {
                    if ($node->filter('td')->count() > 1) {
                        $programName = trim($node->filter('td')->eq(1)->text());
                        
                        // Add a check to skip the header row
                        if (!empty($programName) && $programName !== "Name of Qualification") {
                            $cleanedName = preg_replace('/\s+/', ' ', $programName);
                            Program::create(['name' => $cleanedName]);
                            $this->line("  -> Found: " . $cleanedName);
                            $totalFound++;
                        }
                    }
                });
            } else {
                 $this->warn("  -> Could not find the data table on this page.");
            }
            $this->info("Scraping completed! Found and saved $totalFound programs.");
        } else {
            $this->error("Failed to fetch URL: $url (Status: " . $response->status() . ")");
        }
    }
}
