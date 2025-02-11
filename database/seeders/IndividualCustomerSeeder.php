<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndividualCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('individual_customers')->insert([
            [
                'login_code' => 'IND001',
                'card_code' => 'CAD001',
                'full_name' => 'Nguyễn Thị A',
                'position' => 'Giám đốc',
                'birth_date' => '1985-05-15',
                'gender' => 'Nữ',
                'phone' => '0123456789',
                'email' => 'nguyen.a@example.com',
                'unit' => 'Công ty A',
                'is_board_member' => true,
                'board_position' => 'Chủ tịch',
                'term' => '2024-2026',
                'industry_id' => 1,  
                'field_id' => 1,     
                'club_id' => 1,      
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_code' => 'IND002',
                'card_code' => 'CAD002',
                'full_name' => 'Trần Văn B',
                'position' => 'Phó Giám đốc',
                'birth_date' => '1990-10-20',
                'gender' => 'Nam',
                'phone' => '0987654321',
                'email' => 'tran.b@example.com',
                'unit' => 'Công ty B',
                'is_board_member' => false,
                'board_position' => null,
                'term' => null,
                'industry_id' => 2,  
                'field_id' => 2,    
                'club_id' => 2,      
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_code' => 'IND003',
                'card_code' => 'CAD003',
                'full_name' => 'Lê Thị C',
                'position' => 'Nhân viên',
                'birth_date' => '1995-08-12',
                'gender' => 'Nữ',
                'phone' => '0345678901',
                'email' => 'le.c@example.com',
                'unit' => 'Công ty C',
                'is_board_member' => false,
                'board_position' => null,
                'term' => null,
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
