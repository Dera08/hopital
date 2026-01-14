<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Vous devez spécifier la table 'invoices' ici
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('admission_id')
                  ->after('patient_id') // Pour une structure propre
                  ->nullable()
                  ->constrained()
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Indispensable pour pouvoir revenir en arrière
            $table->dropForeign(['admission_id']);
            $table->dropColumn('admission_id');
        });
    }
};