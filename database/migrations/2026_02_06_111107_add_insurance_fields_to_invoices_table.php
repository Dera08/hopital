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
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('insurance_card_number')->nullable()->after('payment_operator');
            $table->integer('insurance_coverage_rate')->nullable()->after('insurance_card_number')->comment('Percentage, e.g. 70');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['insurance_card_number', 'insurance_coverage_rate']);
        });
    }
};
