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
        // On ajoute une vérification : si la colonne n'existe PAS, on la crée.
        // Si elle existe déjà, on ne fait rien, ce qui évite l'erreur SQL.
        if (!Schema::hasColumn('hospitals', 'is_active')) {
            Schema::table('hospitals', function (Blueprint $table) {
                $table->boolean('is_active')->default(true)->after('address');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('hospitals', 'is_active')) {
            Schema::table('hospitals', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};