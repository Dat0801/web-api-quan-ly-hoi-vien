<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id'); // ID khách hàng
            $table->string('customer_type'); // Loại khách hàng
            $table->year('year'); // Năm
            $table->decimal('amount_due', 15, 2); // Số tiền phải thu
            $table->decimal('amount_paid', 15, 2)->default(0); // Số tiền đã đóng
            $table->decimal('remaining_amount', 15, 2); // Số tiền còn thiếu
            $table->boolean('status')->default(false); // Trạng thái: đã tất toán hay chưa
            $table->text('content')->nullable(); // Nội dung đóng hội phí
            $table->string('attachment')->nullable(); // File đính kèm
            $table->date('payment_date')->nullable(); // Ngày đóng
            $table->boolean('is_early_payment')->default(false); // Đánh dấu trường hợp đóng trước
            $table->integer('payment_years')->nullable(); // Số năm đóng trước
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('membership_fees');
    }
};
