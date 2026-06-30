<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop rental tables — rental feature removed as per product decision (2026-06-28).
     * equipment_rentals must be dropped before rental_equipments (FK dependency).
     */
    public function up(): void
    {
        Schema::dropIfExists('equipment_rentals');
        Schema::dropIfExists('rental_equipments');
    }

    public function down(): void
    {
        // Intentionally left empty — this feature has been permanently removed.
    }
};
