<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Business;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Business::create([
            'business_code' => 'DN86',
            'business_name' => 'Hãng công nghệ',
        ]);

        Business::create([
            'business_code' => 'DN66',
            'business_name' => 'Doanh nghiệp tiềm năng',
        ]);
    }
}
