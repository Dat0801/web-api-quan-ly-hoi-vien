<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Market;

class MarketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Market::insert([
            [
                'market_code' => 'TT43',
                'market_name' => 'Giao thông vận tải',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'market_code' => 'TT53',
                'market_name' => 'Y tế',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'market_code' => 'TT60',
                'market_name' => 'Du lịch',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'market_code' => 'TT99',
                'market_name' => 'Tài nguyên - môi trường',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Market::factory(10)->create();
    }
}
