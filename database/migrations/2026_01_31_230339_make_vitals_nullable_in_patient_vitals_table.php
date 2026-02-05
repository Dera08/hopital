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
        Schema::table('patient_vitals', function (Blueprint $table) {
            $table->float('temperature')->nullable()->change();
            $table->integer('pulse')->nullable()->change();
            $table->string('blood_pressure')->nullable()->change();
            // Checking others just in case, though weight was already nullable in original migration
            $table->float('weight')->nullable()->change(); 
            // Also need to check if 'user_id' can be nullable, as the controller passes 'user_id' => null
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_vitals', function (Blueprint $table) {
            // We can't easily revert nullable->not null without knowing default values for existing nulls
            // For now, we'll leave it as is or could potentially revert if we were strict. 
            // In a real scenario, we might want to be more careful.
            $table->float('temperature')->nullable(false)->change();
            $table->integer('pulse')->nullable(false)->change();
            $table->string('blood_pressure')->nullable(false)->change();
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
