<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add image_path column to spots for local file uploads.
     * image_url (existing) remains for backwards-compat with seeded external URLs.
     * image_path takes priority when set.
     */
    public function up(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('image_url');
        });
    }

    public function down(): void
    {
        Schema::table('spots', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
};
