<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ['role_id'=> 'VT0123','role_name' => 'Admin'],
            ['role_id'=> 'VT0124','role_name' => 'User'],
        ]);
    }
}
