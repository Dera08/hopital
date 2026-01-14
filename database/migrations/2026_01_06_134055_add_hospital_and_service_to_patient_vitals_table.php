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
        Schema::table('patient_vitals', function (Blueprint $table) {
            $table->foreignId('hospital_id')->after('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->after('hospital_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_vitals', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropForeign(['hospital_id']);
            $table->dropColumn(['service_id', 'hospital_id']);
        });
    }
};
