<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * spots table — Point data for GIS map markers.
     * Columns: id, name, category, latitude, longitude, description,
     *          image_url, is_sponsored, promo_detail, timestamps
     */
    public function up(): void
    {
        Schema::create('spots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->double('latitude');
            $table->double('longitude');
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_sponsored')->default(false);
            $table->text('promo_detail')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spots');
    }
};
