<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Vérifier et ajouter les colonnes manquantes pour l'authentification
            if (!Schema::hasColumn('patients', 'password')) {
                $table->string('password')->after('email');
            }
            
            if (!Schema::hasColumn('patients', 'remember_token')) {
                $table->rememberToken();
            }
            
            // Ajouter le médecin référent
            if (!Schema::hasColumn('patients', 'referring_doctor_id')) {
                $table->foreignId('referring_doctor_id')
                    ->nullable()
                    ->after('medical_history')
                    ->constrained('users')
                    ->nullOnDelete();
                    
                // Ajouter l'index
                $table->index('referring_doctor_id');
            }
            
            // S'assurer que l'email est unique
            if (!Schema::hasColumn('patients', 'email')) {
                // Si la colonne n'existe pas, l'ajouter
                $table->string('email')->unique()->after('phone');
            }
            // Note: L'email est déjà défini comme unique dans la migration originale des patients
            // Pas besoin de le modifier ici
            
            // Modifier le type de la colonne address si nécessaire
            $table->text('address')->nullable()->change();
            
            // Modifier l'enum gender pour correspondre au modèle
            $table->enum('gender', ['Homme', 'Femme', 'Autre'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            // Supprimer la clé étrangère et l'index
            $table->dropForeign(['referring_doctor_id']);
            $table->dropIndex(['referring_doctor_id']);
            $table->dropColumn('referring_doctor_id');
            
            // Supprimer les colonnes d'authentification
            $table->dropColumn(['password', 'remember_token']);
        });
    }
};