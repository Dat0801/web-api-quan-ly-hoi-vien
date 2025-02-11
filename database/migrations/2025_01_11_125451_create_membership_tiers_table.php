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
        Schema::create('membership_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên hạng thành viên
            $table->text('description')->nullable(); // Mô tả về hạng thành viên
            $table->decimal('fee', 15, 2); // Mức phí phải nộp
            $table->decimal('minimum_contribution', 15, 2); // Đóng góp tối thiểu
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
        Schema::dropIfExists('membership_tiers');
    }
};
