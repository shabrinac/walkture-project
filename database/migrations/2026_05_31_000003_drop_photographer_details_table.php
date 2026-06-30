<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop tabel photographer_details lama dengan CASCADE
     * karena tabel ini direferensikan oleh bookings dan digital_assets.
     * Digantikan oleh tabel photographers_directory yang mandiri.
     */
    public function up(): void
    {
        // Gunakan Schema::dropIfExists agar kompatibel di semua driver (SQLite, PostgreSQL)
        Schema::dropIfExists('photographer_details');
    }

    public function down(): void
    {
        // Tidak di-restore karena sudah digantikan oleh photographers_directory
    }
};
