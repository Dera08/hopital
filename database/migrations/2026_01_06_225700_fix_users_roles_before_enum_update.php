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
        // Fix existing roles before enum update
        DB::table('users')->where('role', 'internal_doctor')->update(['role' => 'doctor']);
        DB::table('users')->where('role', 'external_doctor')->update(['role' => 'doctor']);
        // Set any other invalid roles to 'administrative'
        DB::table('users')->whereNotIn('role', ['admin', 'doctor', 'nurse', 'administrative', 'cashier'])->update(['role' => 'administrative']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
