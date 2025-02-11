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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tên hoạt động
            $table->datetime('start_time'); // Thời gian bắt đầu
            $table->datetime('end_time'); // Thời gian kết thúc
            $table->string('location'); // Địa điểm
            $table->text('content')->nullable(); // Nội dung hoạt động
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
        Schema::dropIfExists('activities');
    }
};
