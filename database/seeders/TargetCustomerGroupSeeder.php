<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TargetCustomerGroup;

class TargetCustomerGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $customerGroups = [
            ['group_code' => 'KH43', 'group_name' => 'Cơ quan, tổ chức nhà nước'],
            ['group_code' => 'KH53', 'group_name' => 'Doanh nghiệp lớn'],
            ['group_code' => 'KH60', 'group_name' => 'Doanh nghiệp vừa và nhỏ'],
            ['group_code' => 'KH99', 'group_name' => 'Doanh nghiệp siêu nhỏ'],
        ];
        
        foreach ($customerGroups as $group) {
            TargetCustomerGroup::create($group);
        }
        
    }
}
