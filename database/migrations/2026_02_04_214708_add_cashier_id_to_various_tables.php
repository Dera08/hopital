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
            $table->unsignedBigInteger('cashier_id')->nullable()->after('hospital_id');
            $table->foreign('cashier_id')->references('id')->on('users');
        });

        Schema::table('walk_in_consultations', function (Blueprint $table) {
            $table->unsignedBigInteger('cashier_id')->nullable()->after('hospital_id');
            $table->foreign('cashier_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['cashier_id']);
            $table->dropColumn('cashier_id');
        });

        Schema::table('walk_in_consultations', function (Blueprint $table) {
            $table->dropForeign(['cashier_id']);
            $table->dropColumn('cashier_id');
        });
    }
};
