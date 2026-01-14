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
        Schema::table('beds', function (Blueprint $table) {
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade');
        });

        // Populate hospital_id for existing beds based on their room's hospital_id
        DB::statement('UPDATE beds SET hospital_id = (SELECT hospital_id FROM rooms WHERE rooms.id = beds.room_id)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('beds', function (Blueprint $table) {
            $table->dropForeign(['hospital_id']);
            $table->dropColumn('hospital_id');
        });
    }
};
