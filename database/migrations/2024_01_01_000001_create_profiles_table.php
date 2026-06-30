<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * profiles table — Supabase-compatible user profile store.
     * id is UUID to match Supabase Auth UIDs.
     * Columns: id, full_name, role, avatar_url, created_at
     * Removed (obsolete): bio, credits, is_premium
     */
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('full_name');
            $table->string('role')->default('user');   // 'user' | 'admin'
            $table->string('avatar_url')->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
