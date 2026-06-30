<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add polygon_geojson and route_geojson columns to spots table.
     * These store visual GeoJSON drawn by the Admin via Leaflet-Geoman.
     * The old zone_polygon / nearest_route text columns are preserved.
     */
    public function up(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->text('polygon_geojson')->nullable()->after('nearest_route')
                  ->comment('GeoJSON Feature (Polygon) drawn by admin via Leaflet-Geoman');
            $table->text('route_geojson')->nullable()->after('polygon_geojson')
                  ->comment('GeoJSON Feature (LineString) drawn by admin via Leaflet-Geoman');
        });
    }

    public function down(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->dropColumn(['polygon_geojson', 'route_geojson']);
        });
    }
};
