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
        Schema::create('sponsorships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sponsorable_id'); // ID của khách hàng
            $table->string('sponsorable_type'); // Loại khách hàng
            $table->date('sponsorship_date'); // Ngày tài trợ
            $table->text('content')->nullable(); // Nội dung tài trợ
            $table->string('product')->nullable(); // Sản phẩm tài trợ
            $table->string('unit')->nullable(); // Đơn vị
            $table->decimal('unit_price', 15, 2)->nullable(); // Đơn giá
            $table->integer('quantity')->default(1); // Số lượng
            $table->decimal('total_amount', 15, 2)->nullable(); // Thành tiền
            $table->string('attachment')->nullable(); // File đính kèm
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
        Schema::dropIfExists('sponsorships');
    }
};
