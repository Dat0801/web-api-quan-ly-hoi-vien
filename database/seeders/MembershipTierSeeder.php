<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MembershipTier;

class MembershipTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tiers = [
            [
                'name' => 'Thành viên bạc',
                'description' => 'Hạng thành viên cơ bản với quyền lợi hạn chế.',
                'fee' => 1500000,
                'minimum_contribution' => 500000, 
            ],
            [
                'name' => 'Thành viên vàng',
                'description' => 'Hạng thành viên vàng với nhiều quyền lợi.',
                'fee' => 2000000, 
                'minimum_contribution' => 1000000, 
            ],
            [
                'name' => 'Thành viên bạch kim',
                'description' => 'Hạng thành viên cao cấp với tất cả quyền lợi.',
                'fee' => 5000000, 
                'minimum_contribution' => 3000000, 
            ],
        ];

        foreach ($tiers as $tier) {
            MembershipTier::create($tier);
        }
    }
}
