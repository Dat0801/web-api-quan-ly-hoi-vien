<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BoardCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('board_customers')->insert([
            [
                'login_code' => 'BOD001',
                'card_code' => 'CAD001',
                'full_name' => 'Nguyễn Văn A',
                'birth_date' => '1990-01-01',
                'gender' => 'Nam',
                'phone' => '0123456789',
                'email' => 'nguyen.a@example.com',
                'unit_name' => 'Đơn vị A',
                'unit_position' => 'Giám đốc',
                'association_position' => 'Chủ tịch',
                'term' => '2024-2025',
                'attendance_permission' => true,
                'club_id' => 1,  
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_code' => 'BOD002',
                'card_code' => 'CAD002',
                'full_name' => 'Trần Thị B',
                'birth_date' => '1992-02-02',
                'gender' => 'Nữ',
                'phone' => '0987654321',
                'email' => 'tran.b@example.com',
                'unit_name' => 'Đơn vị B',
                'unit_position' => 'Phó Giám đốc',
                'association_position' => 'Phó Chủ tịch',
                'term' => '2024-2025',
                'attendance_permission' => false,
                'club_id' => 2, 
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_code' => 'BOD003',
                'card_code' => 'CAD003', 
                'full_name' => 'Lê Văn C',
                'birth_date' => '1988-03-03',
                'gender' => 'Nam',
                'phone' => '0345678901',
                'email' => 'le.c@example.com',
                'unit_name' => 'Đơn vị C',
                'unit_position' => 'Trưởng phòng',
                'association_position' => 'Ủy viên',
                'term' => '2024-2025',
                'attendance_permission' => true,
                'club_id' => null,  
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
