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
            if (!Schema::hasColumn('admissions', 'bed_id')) {
                $table->foreignId('bed_id')->nullable()->constrained('beds')->nullOnDelete()->after('room_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            if (Schema::hasColumn('admissions', 'bed_id')) {
                $table->dropForeign(['bed_id']);
                $table->dropColumn('bed_id');
            }
        });
    }
};
