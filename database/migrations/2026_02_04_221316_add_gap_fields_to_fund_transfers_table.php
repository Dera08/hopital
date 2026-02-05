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
        Schema::table('fund_transfers', function (Blueprint $table) {
            $table->decimal('received_amount', 15, 2)->nullable()->after('amount');
            $table->decimal('gap_amount', 15, 2)->nullable()->after('received_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fund_transfers', function (Blueprint $table) {
            $table->dropColumn(['received_amount', 'gap_amount']);
        });
    }
};
