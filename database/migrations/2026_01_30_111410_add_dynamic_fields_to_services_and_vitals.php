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
        Schema::table('services', function (Blueprint $table) {
            $table->json('form_config')->nullable()->after('description');
        });

        Schema::table('patient_vitals', function (Blueprint $table) {
            $table->json('custom_vitals')->nullable()->after('height');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('form_config');
        });

        Schema::table('patient_vitals', function (Blueprint $table) {
            $table->dropColumn('custom_vitals');
        });
    }
};
