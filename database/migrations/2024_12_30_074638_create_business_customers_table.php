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
        Schema::create('business_customers', function (Blueprint $table) {
            $table->id();
            $table->string('login_code')->unique(); // Mã đăng nhập
            $table->string('card_code')->unique(); // Mã thẻ
            $table->string('business_name_vi'); // Tên doanh nghiệp (Tiếng Việt)
            $table->string('business_name_en')->nullable(); // Tên doanh nghiệp (Tiếng Anh)
            $table->string('business_name_abbr')->nullable(); // Tên viết tắt
            $table->string('headquarters_address'); // Địa chỉ trụ sở chính
            $table->string('branch_address')->nullable(); // Địa chỉ văn phòng giao dịch
            $table->string('tax_code')->nullable(); // Mã số thuế
            $table->string('phone'); // Số điện thoại
            $table->string('website')->nullable(); // Website
            $table->string('fanpage')->nullable(); // Fanpage
            $table->date('established_date')->nullable(); // Ngày thành lập
            $table->decimal('charter_capital', 15, 2)->nullable(); // Vốn điều lệ
            $table->decimal('pre_membership_revenue', 15, 2)->nullable(); // Doanh thu trước gia nhập
            $table->string('email')->nullable(); // Email
            $table->enum('business_scale', ['50-100', '100-200', '200-500', '500+'])->default('50-100'); // Quy mô doanh nghiệp
            $table->foreignId('industry_id')->nullable()->constrained('industries')->nullOnDelete(); // Tham chiếu ngành
            $table->foreignId('field_id')->nullable()->constrained('fields')->nullOnDelete(); // Tham chiếu lĩnh vực
            $table->foreignId('market_id')->nullable()->constrained('markets')->nullOnDelete(); // Tham chiếu thị trường
            $table->foreignId('target_customer_group_id')->nullable()->constrained('target_customer_groups')->nullOnDelete(); // Tham chiếu khách hàng mục tiêu
            $table->foreignId('certificate_id')->nullable()->constrained('certificates')->nullOnDelete(); // Tham chiếu chứng chỉ
            $table->text('awards')->nullable(); // Nhập giải thưởng
            $table->text('commendations')->nullable(); // Nhập bằng khen
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
        Schema::dropIfExists('business_customers');
    }
};
