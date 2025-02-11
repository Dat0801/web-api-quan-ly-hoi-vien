<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FieldSubGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fields = [
            [
                'name' => 'Tổ chức sự kiện',
                'code' => 'BCH22',
                'description' => 'Tổ chức các sự kiện, có thể thuộc nhiều ngành',
                'industry_id' => 1, 
            ],
            [
                'name' => 'Sản xuất',
                'code' => 'BCH16',
                'description' => 'Ngành sản xuất, có thể thuộc nhiều ngành như Công nghệ thông tin',
                'industry_id' => 2, 
            ],
            [
                'name' => 'Phân phối',
                'code' => 'BCH34',
                'description' => 'Ngành phân phối, có thể thuộc nhiều ngành',
                'industry_id' => 3, 
            ],
            [
                'name' => 'Xây dựng công trình',
                'code' => 'BCH27',
                'description' => 'Ngành xây dựng công trình',
                'industry_id' => 4, 
            ],
        ];

        foreach ($fields as $field) {
            DB::table('fields')->insert($field);
        }

        $subGroups = [
            [
                'name' => 'Nhóm con sự kiện 1',
                'description' => 'Nhóm con tổ chức sự kiện đầu tiên',
                'field_id' => 1,
            ],
            [
                'name' => 'Nhóm con sự kiện 2',
                'description' => 'Nhóm con tổ chức sự kiện thứ hai',
                'field_id' => 1, 
            ],
            [
                'name' => 'Nhóm con sản xuất 1',
                'description' => 'Nhóm con sản xuất công nghệ thông tin',
                'field_id' => 2,
            ],
            [
                'name' => 'Nhóm con phân phối 1',
                'description' => 'Nhóm con ngành phân phối',
                'field_id' => 3, 
            ],
            [
                'name' => 'Nhóm con xây dựng 1',
                'description' => 'Nhóm con ngành xây dựng công trình',
                'field_id' => 4, 
            ],
        ];

        // Dữ liệu vào bảng sub_groups
        foreach ($subGroups as $subGroup) {
            DB::table('sub_groups')->insert($subGroup);
        }
    }
}
