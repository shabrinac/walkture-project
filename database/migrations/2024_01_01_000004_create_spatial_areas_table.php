<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * spatial_areas table — Line/Polygon GIS data.
     * Columns: id, name, type (route/zone), geo_data (JSONB),
     *          distance_or_area, timestamps
     */
    public function up(): void
    {
        Schema::create('spatial_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');          // 'route' | 'zone'
            $table->jsonb('geo_data');       // GeoJSON-compatible payload
            $table->float('distance_or_area')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spatial_areas');
    }
};
