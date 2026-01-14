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
        $table->decimal('weight', 5, 2)->nullable()->after('user_id');
        $table->integer('height')->nullable()->after('weight');
        $table->decimal('temperature', 4, 1)->nullable()->after('height');
        $table->integer('pulse')->nullable()->after('temperature');
        $table->string('blood_pressure', 10)->nullable()->after('pulse');
    });
}

public function down(): void
{
    Schema::table('clinical_observations', function (Blueprint $table) {
        $table->dropColumn(['weight', 'height', 'temperature', 'pulse', 'blood_pressure']);
    });
}
};
