<?php

namespace Database\Seeders;

use App\Models\Campus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class CampusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use the scraping command to populate UiTM campuses from MQA website
        $this->command->info('Scraping UiTM campuses from MQA website...');
        
        Artisan::call('scrape:uitm-campuses');
        
        $this->command->info('UiTM campuses have been scraped and populated successfully.');
        $this->command->info('Total campuses: ' . Campus::count());
    }
}
