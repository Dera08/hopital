<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
{
    Schema::table('clinical_observations', function (Blueprint $table) {
        // Ajoute la colonne manquante
         $table->boolean('is_critical')->default(false);
    });
}

public function down(): void
{
    Schema::table('clinical_observations', function (Blueprint $table) {
        $table->dropColumn('is_critical');
    });
}
};
