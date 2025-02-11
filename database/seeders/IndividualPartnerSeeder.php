<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndividualPartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('individual_partners')->insert([
            [
                'login_code' => 'INP001',
                'card_code' => 'CAD001',
                'full_name' => 'Nguyễn Văn X',
                'position' => 'Giám đốc',
                'phone' => '0123456789',
                'partner_category' => 'Việt Nam',
                'unit' => 'Công ty A',
                'industry_id' => 1, 
                'field_id' => 1,    
                'club_id' => 1,    
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_code' => 'INP002',
                'card_code' => 'CARD002',
                'full_name' => 'Trần Thị Y',
                'position' => 'Phó Giám đốc',
                'phone' => '0987654321',
                'partner_category' => 'Quốc tế',
                'unit' => 'Công ty B',
                'industry_id' => 2, 
                'field_id' => 2,     
                'club_id' => 2,     
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_code' => 'INP003',
                'card_code' => 'CARD003',
                'full_name' => 'Lê Văn Z',
                'position' => 'Trưởng phòng',
                'phone' => '0345678901',
                'partner_category' => 'Việt Nam',
                'unit' => 'Công ty C',
                'industry_id' => null,  
                'field_id' => null,   
                'club_id' => null,     
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
