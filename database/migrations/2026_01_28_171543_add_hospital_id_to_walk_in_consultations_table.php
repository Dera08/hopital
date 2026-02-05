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
        Schema::table('walk_in_consultations', function (Blueprint $table) {
            if (!Schema::hasColumn('walk_in_consultations', 'hospital_id')) {
                $table->foreignId('hospital_id')->nullable()->after('id')->constrained('hospitals')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('walk_in_consultations', function (Blueprint $table) {
            //
        });
    }
};
