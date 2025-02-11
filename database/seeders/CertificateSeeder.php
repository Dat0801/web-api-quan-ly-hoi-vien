<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Certificate;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Certificate::create([
            'certificate_code' => 'CC52',
            'certificate_name' => 'Chứng chỉ Hurta',
        ]);

        Certificate::create([
            'certificate_code' => 'CC53',
            'certificate_name' => 'Chứng chỉ Balentine',
        ]);

        Certificate::create([
            'certificate_code' => 'CC54',
            'certificate_name' => 'Chứng chỉ Gilhooley',
        ]);

        Certificate::create([
            'certificate_code' => 'CC55',
            'certificate_name' => 'Chứng chỉ Averalo',
        ]);
    }
}
