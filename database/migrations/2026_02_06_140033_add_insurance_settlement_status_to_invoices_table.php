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
        Schema::table('invoices', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->string('insurance_settlement_status')->nullable()->after('insurance_coverage_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('insurance_settlement_status');
        });
    }
};
