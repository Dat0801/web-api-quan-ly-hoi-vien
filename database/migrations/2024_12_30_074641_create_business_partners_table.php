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
        Schema::create('business_partners', function (Blueprint $table) {
            $table->id();
            $table->string('login_code')->unique(); // Mã đăng nhập
            $table->string('card_code')->unique(); // Mã thẻ
            $table->string('business_name_vi'); // Tên DN (Tiếng Việt)
            $table->string('business_name_en')->nullable(); // Tên DN (Tiếng Anh)
            $table->string('business_name_abbr')->nullable(); // Tên DN (Viết tắt)
            $table->enum('partner_category', ['Việt Nam', 'Quốc tế']); // Phân loại
            $table->string('headquarters_address'); // Địa chỉ trụ sở chính
            $table->string('branch_address')->nullable(); // Địa chỉ văn phòng giao dịch
            $table->string('tax_code')->nullable(); // Mã số thuế
            $table->string('phone'); // Số điện thoại
            $table->string('website')->nullable(); // Website
            $table->string('leader_name')->nullable(); // Tên lãnh đạo
            $table->string('leader_position')->nullable(); // Chức vụ lãnh đạo
            $table->string('leader_phone')->nullable(); // Số điện thoại lãnh đạo
            $table->string('leader_gender')->nullable(); // Giới tính lãnh đạo
            $table->string('leader_email')->nullable(); // Email lãnh đạo
            $table->foreignId('club_id')->nullable()->constrained('clubs')->nullOnDelete(); // Tham chiếu câu lạc bộ
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
        Schema::dropIfExists('business_partners');
    }
};
