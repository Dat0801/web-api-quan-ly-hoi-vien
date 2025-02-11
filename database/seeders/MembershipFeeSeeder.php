<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MembershipFee;
use App\Models\BoardCustomer;
use App\Models\BusinessCustomer;
use App\Models\IndividualCustomer;
use App\Models\BusinessPartner;
use App\Models\IndividualPartner;

class MembershipFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $currentYear = now()->year; // Năm hiện tại
        $feePerYear = 1500000; // Số tiền hội phí mỗi năm
        $customerTables = [
            ['type' => BoardCustomer::class],
            ['type' => BusinessCustomer::class],
            ['type' => IndividualCustomer::class],
            ['type' => BusinessPartner::class],
            ['type' => IndividualPartner::class],
        ];

        foreach ($customerTables as $customerTable) {
            $customers = $customerTable['type']::whereNull('club_id')->get();

            foreach ($customers as $customer) {
                for ($year = 2024; $year <= $currentYear; $year++) {
                    // Kiểm tra nếu bản ghi hội phí cho năm đó chưa tồn tại
                    if (
                        !MembershipFee::where('customer_id', $customer->id)
                            ->where('customer_type', $customerTable['type'])
                            ->where('year', $year)->exists()
                    ) {
                        MembershipFee::create([
                            'customer_id' => $customer->id,
                            'customer_type' => $customerTable['type'], // Loại khách hàng
                            'year' => $year,
                            'amount_due' => $feePerYear,
                            'amount_paid' => 0, // Chưa đóng
                            'remaining_amount' => $feePerYear, // Nợ toàn bộ
                            'status' => false, // Chưa tất toán
                            'content' => 'Hội phí năm ' . $year,
                            'attachment' => null,
                            'payment_date' => null,
                            'is_early_payment' => false,
                            'payment_years' => null,
                        ]);
                    }
                }
            }
        }
    }
}
