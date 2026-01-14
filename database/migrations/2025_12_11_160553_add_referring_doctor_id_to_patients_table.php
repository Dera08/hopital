<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Ajout de la clé étrangère nullable
            $table->foreignId('referring_doctor_id')
                  ->nullable()
                  ->after('medical_history') // Placé après un champ logique
                      ->constrained('users') // Contrainte sur la table users
                  ->nullOnDelete();      // Si l'utilisateur est supprimé, la référence devient NULL
        });
    }

 
// Fichier : database/migrations/2025_12_11_160553_add_referring_doctor_id_to_patients_table.php

public function down(): void
{
    Schema::table('patients', function (Blueprint $table) {
        // --- NOUVELLE LOGIQUE DE VÉRIFICATION ---
        
        // 1. Suppression Conditionnelle de la Clé Étrangère
        // On utilise dropForeign avec le nom attendu par Laravel (nom_table_nom_colonne_foreign)
        if (Schema::hasColumn('patients', 'referring_doctor_id')) {
            $table->dropForeign(['referring_doctor_id']); 
        }

        // 2. Suppression Conditionnelle de la Colonne
        if (Schema::hasColumn('patients', 'referring_doctor_id')) {
            $table->dropColumn('referring_doctor_id');
        }

        // ---------------------------------------
    });
}
}; 