<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('contacts')->insert([
            [
                'hotline' => '(+84) 283 932 0999',
                'website' => 'www.hca.org.vn',
                'fanpage' => 'HCAVietNam',
                'email' => 'hca@hca.org.vn',
                'address' => '224 Điện Biên Phủ, Phường Võ Thị Sáu, Quận 3, Thành phố Hồ Chí Minh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
