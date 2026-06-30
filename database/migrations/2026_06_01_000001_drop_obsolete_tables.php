<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop all obsolete tables that are no longer part of the PRD schema.
     * These tables were part of older feature sets (credits, scrapbook,
     * spot-unlocking, financial tracking) that have been removed.
     *
     * Order matters: drop tables with FK dependencies first.
     */
    public function up(): void
    {
        // unlocked_spots references both profiles and spots
        Schema::dropIfExists('unlocked_spots');

        // bookings references spots and profiles
        Schema::dropIfExists('bookings');

        // digital_ephemera is a standalone table
        Schema::dropIfExists('digital_ephemera');

        // financial_transactions is a standalone table
        Schema::dropIfExists('financial_transactions');
    }

    public function down(): void
    {
        // These tables are intentionally not restored in rollback.
        // They represent removed features. If needed, restore from git history.
    }
};
