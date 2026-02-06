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
            $table->string('insurance_name')->nullable()->after('status');
            $table->string('insurance_card_number')->nullable()->after('insurance_name');
            $table->integer('insurance_coverage_rate')->nullable()->after('insurance_card_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('walk_in_consultations', function (Blueprint $table) {
            $table->dropColumn(['insurance_name', 'insurance_card_number', 'insurance_coverage_rate']);
        });
    }
};
