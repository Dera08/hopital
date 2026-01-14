<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // SQLite doesn't support MODIFY COLUMN, but ENUM is stored as TEXT
        // No migration needed as the status column already accepts text values
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed for SQLite
    }
};
