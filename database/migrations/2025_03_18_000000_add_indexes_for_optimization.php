<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Business Customers table indexes
        Schema::table('business_customers', function (Blueprint $table) {
            // Index for frequently searched fields
            $table->index('business_name_vi');
            $table->index('tax_code');
            $table->index('phone');
            $table->index('email');
            $table->index('status');
            $table->index(['industry_id', 'field_id', 'market_id']);
            $table->index('club_id');
        });

        // Business Partners table indexes
        Schema::table('business_partners', function (Blueprint $table) {
            $table->index('business_name_vi');
            $table->index('partner_category');
            $table->index('tax_code');
            $table->index('phone');
            $table->index('status');
            $table->index('club_id');
        });

        // Add indexes to other related tables
        Schema::table('industries', function (Blueprint $table) {
            $table->index('industry_name');
        });

        Schema::table('fields', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('markets', function (Blueprint $table) {
            $table->index('market_name');
        });

        Schema::table('clubs', function (Blueprint $table) {
            $table->index('name_vi');
        });
    }

    public function down()
    {
        // Remove indexes from business_customers
        Schema::table('business_customers', function (Blueprint $table) {
            $table->dropIndex(['business_name_vi']);
            $table->dropIndex(['tax_code']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['email']);
            $table->dropIndex(['status']);
            $table->dropIndex(['industry_id', 'field_id', 'market_id']);
            $table->dropIndex(['club_id']);
        });

        // Remove indexes from business_partners
        Schema::table('business_partners', function (Blueprint $table) {
            $table->dropIndex(['business_name_vi']);
            $table->dropIndex(['partner_category']);
            $table->dropIndex(['tax_code']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['status']);
            $table->dropIndex(['club_id']);
        });

        // Remove indexes from other tables
        Schema::table('industries', function (Blueprint $table) {
            $table->dropIndex(['industry_name']);
        });

        Schema::table('fields', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('markets', function (Blueprint $table) {
            $table->dropIndex(['market_name']);
        });

        Schema::table('clubs', function (Blueprint $table) {
            $table->dropIndex(['name_vi']);
        });
    }
}; 