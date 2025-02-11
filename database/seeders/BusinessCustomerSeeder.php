<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('business_customers')->insert([
            [
                'login_code' => 'BUS001',
                'card_code' => 'CAD001',
                'business_name_vi' => 'Doanh nghiệp ABC',
                'business_name_en' => 'ABC Business',
                'business_name_abbr' => 'ABC',
                'headquarters_address' => '123 Đường XYZ, Thành phố ABC',
                'branch_address' => '456 Đường DEF, Thành phố ABC',
                'tax_code' => '123456789',
                'phone' => '0123456789',
                'website' => 'https://abc.com',
                'fanpage' => 'https://facebook.com/abc',
                'established_date' => now(),
                'charter_capital' => 50000000.00,
                'pre_membership_revenue' => 2000000.00,
                'email' => 'contact@abc.com',
                'business_scale' => '50-100',
                'industry_id' => 1, 
                'field_id' => 1, 
                'market_id' => 1, 
                'target_customer_group_id' => 1, 
                'certificate_id' => 1, 
                'awards' => 'Giải thưởng 1, Giải thưởng 2',
                'commendations' => 'Bằng khen 1, Bằng khen 2',
                'leader_name' => 'Nguyễn Văn A',
                'leader_position' => 'Giám đốc',
                'leader_phone' => '0123456789',
                'leader_gender' => 'Nam',
                'leader_email' => 'leader@abc.com',
                'club_id' => 1, 
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_code' => 'BUSINESS002',
                'card_code' => 'CARD002',
                'business_name_vi' => 'Doanh nghiệp XYZ',
                'business_name_en' => 'XYZ Business',
                'business_name_abbr' => 'XYZ',
                'headquarters_address' => '789 Đường GHI, Thành phố XYZ',
                'branch_address' => '101 Đường JKL, Thành phố XYZ',
                'tax_code' => '987654321',
                'phone' => '0987654321',
                'website' => 'https://xyz.com',
                'fanpage' => 'https://facebook.com/xyz',
                'established_date' => now(),
                'charter_capital' => 100000000.00,
                'pre_membership_revenue' => 5000000.00,
                'email' => 'contact@xyz.com',
                'business_scale' => '100-200',
                'industry_id' => 2, 
                'field_id' => 2, 
                'market_id' => 2, 
                'target_customer_group_id' => 2, 
                'certificate_id' => 2, 
                'awards' => 'Giải thưởng 3, Giải thưởng 4',
                'commendations' => 'Bằng khen 3, Bằng khen 4',
                'leader_name' => 'Trần Văn B',
                'leader_position' => 'Giám đốc',
                'leader_phone' => '0987654321',
                'leader_gender' => 'Nữ',
                'leader_email' => 'leader@xyz.com',
                'club_id' => null, 
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login_code' => 'BUSINESS003',
                'card_code' => 'CARD003',
                'business_name_vi' => 'Doanh nghiệp MNO',
                'business_name_en' => 'MNO Business',
                'business_name_abbr' => 'MNO',
                'headquarters_address' => '111 Đường PQR, Thành phố MNO',
                'branch_address' => '222 Đường STU, Thành phố MNO',
                'tax_code' => '111222333',
                'phone' => '0345678901',
                'website' => 'https://mno.com',
                'fanpage' => 'https://facebook.com/mno',
                'established_date' => now(),
                'charter_capital' => 20000000.00,
                'pre_membership_revenue' => 1500000.00,
                'email' => 'contact@mno.com',
                'business_scale' => '200-500',
                'industry_id' => 3,
                'field_id' => 3,
                'market_id' => 3,
                'target_customer_group_id' => 3,
                'certificate_id' => 3,
                'awards' => 'Giải thưởng 5, Giải thưởng 6',
                'commendations' => 'Bằng khen 5, Bằng khen 6',
                'leader_name' => 'Lê Văn C',
                'leader_position' => 'Giám đốc',
                'leader_phone' => '0345678901',
                'leader_gender' => 'Nam',
                'leader_email' => 'leader@mno.com',
                'club_id' => null,  
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
