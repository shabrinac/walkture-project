<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add pricing_packages JSON column to photographers_directory.
     * Stores 4 package tiers: basic, standard, premium, fullday.
     */
    public function up(): void
    {
        Schema::table('photographers_directory', function (Blueprint $table) {
            $table->json('pricing_packages')->nullable()->after('paid_until');
        });
    }

    public function down(): void
    {
        Schema::table('photographers_directory', function (Blueprint $table) {
            $table->dropColumn('pricing_packages');
        });
    }
};
