<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the existing role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Add the role column with updated enum values
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'doctor', 'nurse', 'administrative', 'internal_doctor', 'external_doctor'])->default('administrative')->after('password');
        });
    }

    public function down(): void
    {
        // Drop the current role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Add back the original role column
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'doctor', 'nurse', 'administrative'])->default('administrative')->after('password');
        });
    }
}; 
