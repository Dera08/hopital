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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn(['dosage', 'frequency', 'start_date']);
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('dosage')->nullable()->after('medication');
            $table->string('frequency')->nullable()->after('dosage');
            $table->date('start_date')->nullable()->after('frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn(['dosage', 'frequency', 'start_date']);
        });

        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('dosage')->after('medication');
            $table->string('frequency')->after('dosage');
            $table->date('start_date')->after('frequency');
        });
    }
};
