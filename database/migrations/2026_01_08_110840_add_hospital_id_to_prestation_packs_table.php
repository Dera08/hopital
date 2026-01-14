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
        Schema::table('prestation_packs', function (Blueprint $table) {
            if (!Schema::hasColumn('prestation_packs', 'hospital_id')) {
                $table->unsignedBigInteger('hospital_id')->after('id')->index();
                // Optionnel : ajouter la contrainte de clé étrangère
                // $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestation_packs', function (Blueprint $table) {
            //
        });
    }
};
