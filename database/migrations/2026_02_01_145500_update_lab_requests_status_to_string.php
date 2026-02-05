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
        Schema::table('lab_requests', function (Blueprint $table) {
            $table->string('status', 50)->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_requests', function (Blueprint $table) {
            // Reverting to enum might lose data if new statuses were added, 
            // but for safety in dev we can stick to string or re-enum.
            $table->enum('status', ['pending', 'sample_received', 'in_progress', 'completed', 'cancelled'])->default('pending')->change();
        });
    }
};
