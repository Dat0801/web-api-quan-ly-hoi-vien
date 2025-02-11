<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Organization::create([
            'organization_code' => 'TC75',
            'organization_name' => 'Nhà nước',
        ]);

        Organization::create([
            'organization_code' => 'TC81',
            'organization_name' => 'Ngoại giao đoàn',
        ]);

        Organization::create([
            'organization_code' => 'TC49',
            'organization_name' => 'Lãnh sự quán',
        ]);

        Organization::create([
            'organization_code' => 'TC69',
            'organization_name' => 'Hiệp hội - Hội ngành nghề',
        ]);
    }
}
