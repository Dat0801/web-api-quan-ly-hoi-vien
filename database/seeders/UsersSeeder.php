<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            [
                'name' => 'Thành Đạt',
                'password' => Hash::make('password'),
                'email' => 'nguyenbin394@gmail.com',
                'phone_number' => '0399839848',
                'role_id' => 1,
            ],
            [
                'name' => 'Thuận Quang',
                'password' => Hash::make('password'),
                'email' => 'quanglopxe@gmail.com',
                'phone_number' => '0399839888',
                'role_id' => 2,
            ],
        ]);
    }
}
