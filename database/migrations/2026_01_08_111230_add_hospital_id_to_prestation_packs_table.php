<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    // On ajoute cette condition IF
    if (!Schema::hasColumn('prestation_packs', 'hospital_id')) {
        Schema::table('prestation_packs', function (Blueprint $table) {
            $table->unsignedBigInteger('hospital_id')->after('id')->index()->nullable();
        });
    }
}

    public function down(): void
    {
        Schema::table('prestation_packs', function (Blueprint $table) {
            $table->dropColumn('hospital_id');
        });
    }
};
