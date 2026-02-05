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
        Schema::table('clinical_observations', function (Blueprint $table) {
            $table->string('type')->default('vitals')->change();
            $table->text('notes')->nullable()->after('value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinical_observations', function (Blueprint $table) {
            // Revenir à l'enum est difficile sans doctrine/dbal ou manipulations complexes
            // On laisse en string pour la flexibilité
            $table->dropColumn('notes');
        });
    }
};
