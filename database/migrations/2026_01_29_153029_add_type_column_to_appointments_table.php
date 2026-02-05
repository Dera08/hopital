<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if column exists before modifying, though the error confirms it does.
        // We use raw SQL to ensure we can modify it even if dbal is missing or if it's an enum.
        if (Schema::hasColumn('appointments', 'type')) {
            DB::statement("ALTER TABLE appointments MODIFY COLUMN type VARCHAR(255) DEFAULT 'consultation'");
        } else {
            Schema::table('appointments', function (Blueprint $table) {
                $table->string('type')->default('consultation');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No safe reverse without knowing previous state, but we can assume it was acceptable to be string before
        // or we just leave it as string.
    }
};
