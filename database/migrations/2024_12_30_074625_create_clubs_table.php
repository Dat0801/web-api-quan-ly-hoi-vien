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
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('club_code')->unique(); // Mã câu lạc bộ
            $table->string('name_vi'); // Tên câu lạc bộ (Tiếng Việt)
            $table->string('name_en')->nullable(); // Tên câu lạc bộ (Tiếng Anh)
            $table->string('name_abbr')->nullable(); // Tên viết tắt
            $table->string('address')->nullable(); // Địa chỉ
            $table->string('tax_code')->nullable(); // Mã số thuế
            $table->string('phone')->nullable(); // Số điện thoại
            $table->string('email')->nullable(); // Email
            $table->string('website')->nullable(); // Website
            $table->string('fanpage')->nullable(); // Fanpage
            $table->date('established_date')->nullable(); // Ngày thành lập
            $table->string('established_decision')->nullable(); // Quyết định thành lập
            $table->foreignId('industry_id')->nullable()->constrained('industries')->nullOnDelete(); // Tham chiếu đến bảng ngành
            $table->foreignId('field_id')->nullable()->constrained('fields')->nullOnDelete(); // Tham chiếu đến bảng lĩnh vực
            $table->foreignId('market_id')->nullable()->constrained('markets')->nullOnDelete(); // Tham chiếu đến bảng thị trường
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('clubs');
    }
};
