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
        Schema::create('connectors', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('position'); 
            $table->string('phone'); 
            $table->string('gender'); 
            $table->string('email'); 
            $table->foreignId('business_partner_id')->nullable()->constrained('business_partners')->nullOnDelete();
            $table->foreignId('business_customer_id')->nullable()->constrained('business_customers')->nullOnDelete();
            $table->foreignId('club_id')->nullable()->constrained('clubs')->nullOnDelete();
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
        Schema::dropIfExists('connectors');
    }
};
