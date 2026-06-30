<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * photographers_directory — Standalone photographer showcase directory.
     * Managed exclusively by Admin. NOT linked to the users/auth system.
     *
     * Changes from previous version:
     *   - Renamed: name → full_name, photo_url → avatar_url
     *   - Added: portfolio_url, paid_until
     *   - Removed: bio, rating (not in PRD)
     *   - Kept: specialty, whatsapp_link, instagram_link, is_active
     */
    public function up(): void
    {
        Schema::create('photographers_directory', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('specialty');                           // Analog | Street | Portrait | Landscape
            $table->string('avatar_url')->nullable();
            $table->string('portfolio_url')->nullable();           // External portfolio link
            $table->string('whatsapp_link')->nullable();           // wa.me/628...
            $table->string('instagram_link')->nullable();          // instagram.com/...
            $table->boolean('is_active')->default(true);
            $table->timestamp('paid_until')->nullable();           // Null = free listing
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photographers_directory');
    }
};
