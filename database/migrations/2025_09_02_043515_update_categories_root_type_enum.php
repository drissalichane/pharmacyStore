<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum to include new root types
        DB::statement("ALTER TABLE categories MODIFY COLUMN root_type ENUM('Médicaments', 'Parapharmacie', 'Hygiène & Beauté', 'Mère & Bébé') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE categories MODIFY COLUMN root_type ENUM('Médicaments', 'Parapharmacie') NULL");
    }
};
