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
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('payment_transaction_id')->nullable()->after('status');
            $table->string('payment_method')->nullable()->after('payment_transaction_id');
            $table->string('payment_operator')->nullable()->after('payment_method');
        });

        Schema::table('lab_requests', function (Blueprint $table) {
            $table->string('payment_transaction_id')->nullable()->after('is_paid');
            $table->string('payment_method')->nullable()->after('payment_transaction_id');
            $table->string('payment_operator')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['payment_transaction_id', 'payment_method', 'payment_operator']);
        });

        Schema::table('lab_requests', function (Blueprint $table) {
            $table->dropColumn(['payment_transaction_id', 'payment_method', 'payment_operator']);
        });
    }
};
