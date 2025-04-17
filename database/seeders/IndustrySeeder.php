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
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'industry_name' => 'Sản xuất',
                'industry_code' => 'BCH16',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'industry_name' => 'Phân phối',
                'industry_code' => 'BCH34',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'industry_name' => 'Xây dựng',
                'industry_code' => 'BCH27',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Industry::factory(10)->create();
    }
}
