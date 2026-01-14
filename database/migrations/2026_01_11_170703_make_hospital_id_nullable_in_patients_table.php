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
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['hospital_id']);
            $table->foreignId('hospital_id')->nullable()->change();
            $table->foreign('hospital_id')->references('id')->on('hospitals')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['hospital_id']);
            $table->foreignId('hospital_id')->nullable(false)->change();
            $table->foreign('hospital_id')->references('id')->on('hospitals')->cascadeOnDelete();
        });
    }
};
