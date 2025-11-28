<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing faculties first
        Faculty::truncate();
        
        $faculties = [
            ['name' => 'Faculty of Accountancy', 'code' => 'FPA', 'description' => 'Fakulti Perakaunan'],
            ['name' => 'Faculty of Administrative Science & Policy Studies', 'code' => 'FSPPP', 'description' => 'Fakulti Sains Pentadbiran dan Pengajian Polisi'],
            ['name' => 'Faculty of Applied Sciences', 'code' => 'FSG', 'description' => 'Fakulti Sains Gunaan'],
            ['name' => 'Faculty of Architecture, Planning & Surveying', 'code' => 'FSPU', 'description' => 'Fakulti Senibina, Perancangan dan Ukur'],
            ['name' => 'Faculty of Art & Design', 'code' => 'FSR', 'description' => 'Fakulti Seni Lukis dan Seni Reka'],
            ['name' => 'Faculty of Business & Management', 'code' => 'FPP', 'description' => 'Fakulti Perniagaan dan Pengurusan'],
            ['name' => 'Faculty of Chemical Engineering', 'code' => 'FKK', 'description' => 'Fakulti Kejuruteraan Kimia'],
            ['name' => 'Faculty of Civil Engineering', 'code' => 'FKA', 'description' => 'Fakulti Kejuruteraan Awam'],
            ['name' => 'Faculty of Computer & Mathematical Sciences', 'code' => 'FSKM', 'description' => 'Fakulti Sains Komputer dan Matematik'],
            ['name' => 'Faculty of Communication & Media Studies', 'code' => 'FPK', 'description' => 'Fakulti Komunikasi dan Pengajian Media'],
            ['name' => 'Faculty of Dentistry', 'code' => 'FPG', 'description' => 'Fakulti Pergigian'],
            ['name' => 'Faculty of Economics & Management', 'code' => 'FEM', 'description' => 'Fakulti Ekonomi dan Pengurusan'],
            ['name' => 'Faculty of Education', 'code' => 'FPD', 'description' => 'Fakulti Pendidikan'],
            ['name' => 'Faculty of Electrical Engineering', 'code' => 'FKE', 'description' => 'Fakulti Kejuruteraan Elektrik'],
            ['name' => 'Faculty of Film, Theatre & Animation', 'code' => 'FTA', 'description' => 'Fakulti Filem, Teater dan Animasi'],
            ['name' => 'Faculty of Health Sciences', 'code' => 'FSK', 'description' => 'Fakulti Sains Kesihatan'],
            ['name' => 'Faculty of Hotel & Tourism Management', 'code' => 'FPHP', 'description' => 'Fakulti Pengurusan Hotel dan Pelancongan'],
            ['name' => 'Faculty of Information Management', 'code' => 'FPM', 'description' => 'Fakulti Pengurusan Maklumat'],
            ['name' => 'Faculty of Law', 'code' => 'FUU', 'description' => 'Fakulti Undang-undang'],
            ['name' => 'Faculty of Mechanical Engineering', 'code' => 'FKM', 'description' => 'Fakulti Kejuruteraan Mekanikal'],
            ['name' => 'Faculty of Medicine', 'code' => 'FPB', 'description' => 'Fakulti Perubatan'],
            ['name' => 'Faculty of Music', 'code' => 'FMZ', 'description' => 'Fakulti Muzik'],
            ['name' => 'Faculty of Pharmacy', 'code' => 'FFR', 'description' => 'Fakulti Farmasi'],
            ['name' => 'Faculty of Plantation & Agrotechnology', 'code' => 'FPAT', 'description' => 'Fakulti Perladangan dan Agroteknologi'],
            ['name' => 'Faculty of Social Sciences & Humanities', 'code' => 'FSSK', 'description' => 'Fakulti Sains Sosial dan Kemanusiaan'],
            ['name' => 'Faculty of Sports Science & Recreation', 'code' => 'FSSR', 'description' => 'Fakulti Sains Sukan dan Rekreasi'],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }
    }
}
