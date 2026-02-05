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
        // 1. D'abord convertir en VARCHAR pour éviter les erreurs de truncation si l'ordre change ou autre
        DB::statement("ALTER TABLE users MODIFY COLUMN role VARCHAR(50) NOT NULL");
        
        // 2. Ensuite appliquer le nouvel ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'doctor', 'nurse', 'cashier', 'administrative', 'internal_doctor', 'medecin_externe', 'lab_technician') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retirer 'lab_technician' de l'ENUM
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'doctor', 'nurse', 'cashier', 'administrative', 'internal_doctor', 'medecin_externe') NOT NULL");
    }
};
