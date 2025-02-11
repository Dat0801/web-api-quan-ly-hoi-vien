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
        Schema::create('individual_customers', function (Blueprint $table) {
            $table->id();
            $table->string('login_code')->unique(); // Mã đăng nhập
            $table->string('card_code')->unique(); // Mã thẻ
            $table->string('full_name'); // Họ và tên
            $table->string('position')->nullable(); // Chức vụ
            $table->date('birth_date')->nullable(); // Ngày sinh
            $table->string('gender'); // Giới tính
            $table->string('phone'); // Số điện thoại
            $table->string('email'); // Email
            $table->string('unit')->nullable(); // Đơn vị
            $table->boolean('is_board_member')->default(false); // Có thuộc Ban Chấp Hành hay không
            $table->string('board_position')->nullable(); // Chức danh trong BCH (nếu có)
            $table->string('term')->nullable(); // Nhiệm kỳ (nếu có)
            $table->foreignId('industry_id')->nullable()->constrained('industries')->nullOnDelete(); // Tham chiếu ngành
            $table->foreignId('field_id')->nullable()->constrained('fields')->nullOnDelete(); // Tham chiếu lĩnh vực
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
        Schema::dropIfExists('individual_customers');
    }
};
