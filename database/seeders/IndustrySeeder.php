<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Industry;

class IndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Industry::insert([
            [
                'industry_name' => 'Công nghệ thông tin',
                'industry_code' => 'BCH22',
            ],
            [
                'industry_name' => 'Sản xuất',
                'industry_code' => 'BCH16',
            ],
            [
                'industry_name' => 'Phân phối',
                'industry_code' => 'BCH34',
            ],
            [
                'industry_name' => 'Xây dựng',
                'industry_code' => 'BCH27',
            ],
        ]);
    }
}
