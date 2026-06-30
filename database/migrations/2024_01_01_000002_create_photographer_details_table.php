<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photographer_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreign('id')->references('id')->on('profiles')->onDelete('cascade');
            $table->string('specialty');
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->float('rating')->nullable()->default(0.0);
            $table->string('portfolio_url')->nullable();
            $table->boolean('is_verified')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photographer_details');
    }
};
