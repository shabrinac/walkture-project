<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add zone_polygon and nearest_route columns to spots table.
     * These replace the now-defunct spatial_areas pivot approach.
     */
    public function up(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->text('zone_polygon')->nullable()->after('promo_detail');
            $table->text('nearest_route')->nullable()->after('zone_polygon');
        });
    }

    public function down(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->dropColumn(['zone_polygon', 'nearest_route']);
        });
    }
};
