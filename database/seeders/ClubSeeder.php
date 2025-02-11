<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('clubs')->insert([
            [
                'club_code' => 'CLB001',
                'name_vi' => 'Hội PloegerNoelani',
                'name_en' => 'PloegerNoelani Club',
                'name_abbr' => 'PNC',
                'address' => '123 Đường ABC, Thành phố XYZ',
                'tax_code' => '123456789',
                'phone' => '0123456789',
                'email' => 'PloegerNoelani@example.com',
                'website' => 'https://PloegerNoelani.com',
                'fanpage' => 'https://facebook.com/PloegerNoelani',
                'established_date' => now(),
                'established_decision' => 'QĐ-12345',
                'industry_id' => 1, 
                'field_id' => 1,
                'market_id' => 1, 
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'club_code' => 'CLB002',
                'name_vi' => 'Hội GodinoKynthia',
                'name_en' => 'GodinoKynthia Club',
                'name_abbr' => 'GKC',
                'address' => '456 Đường DEF, Thành phố ABC',
                'tax_code' => '987654321',
                'phone' => '0987654321',
                'email' => 'GodinoKynthia@example.com',
                'website' => 'https://GodinoKynthia.com',
                'fanpage' => 'https://facebook.com/GodinoKynthia',
                'established_date' => now(),
                'established_decision' => 'QĐ-67890',
                'industry_id' => 2,
                'field_id' => 2,
                'market_id' => 2,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
