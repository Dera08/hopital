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
        Schema::table('admissions', function (Blueprint $table) {
            if (!Schema::hasColumn('admissions', 'admission_type')) {
                $table->enum('admission_type', ['emergency', 'scheduled', 'transfer'])->default('emergency')->after('admission_date');
            }
            if (!Schema::hasColumn('admissions', 'admission_reason')) {
                $table->text('admission_reason')->nullable()->after('admission_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn(['admission_type', 'admission_reason']);
        });
    }
};
