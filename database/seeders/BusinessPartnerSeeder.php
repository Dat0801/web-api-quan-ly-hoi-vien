<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessPartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('business_partners')->insert([
            [
                'login_code' => 'PNR001',
                'card_code' => 'CAD001',
                'business_name_vi' => 'Đối tác ABC',
                'business_name_en' => 'ABC Partner',
                'business_name_abbr' => 'ABC',
                'partner_category' => 'Việt Nam',
                'headquarters_address' => '123 Đường XYZ, Thành phố ABC',
                'branch_address' => '456 Đường DEF, Thành phố ABC',
                'tax_code' => '123456789',
                'phone' => '0123456789',
                'website' => 'https://abcpartner.com',
                'leader_name' => 'Nguyễn Văn A',
                'leader_position' => 'Giám đốc',
                'leader_phone' => '0123456789',
                'leader_gender' => 'Nam',
                'leader_email' => 'leader@abcpartner.com',
                'club_id' => 1, 
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_code' => 'PNR002',
                'card_code' => 'CAD002',
                'business_name_vi' => 'Đối tác XYZ',
                'business_name_en' => 'XYZ Partner',
                'business_name_abbr' => 'XYZ',
                'partner_category' => 'Quốc tế',
                'headquarters_address' => '789 Đường GHI, Thành phố XYZ',
                'branch_address' => '101 Đường JKL, Thành phố XYZ',
                'tax_code' => '987654321',
                'phone' => '0987654321',
                'website' => 'https://xyzpartner.com',
                'leader_name' => 'Trần Văn B',
                'leader_position' => 'Giám đốc',
                'leader_phone' => '0987654321',
                'leader_gender' => 'Nữ',
                'leader_email' => 'leader@xyzpartner.com',
                'club_id' => null,  
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_code' => 'PNR003',
                'card_code' => 'CAD003',
                'business_name_vi' => 'Đối tác MNO',
                'business_name_en' => 'MNO Partner',
                'business_name_abbr' => 'MNO',
                'partner_category' => 'Việt Nam',
                'headquarters_address' => '111 Đường PQR, Thành phố MNO',
                'branch_address' => '222 Đường STU, Thành phố MNO',
                'tax_code' => '112233445',
                'phone' => '0345678901',
                'website' => 'https://mnopartner.com',
                'leader_name' => 'Lê Văn C',
                'leader_position' => 'Giám đốc',
                'leader_phone' => '0345678901',
                'leader_gender' => 'Nam',
                'leader_email' => 'leader@mnopartner.com',
                'club_id' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
