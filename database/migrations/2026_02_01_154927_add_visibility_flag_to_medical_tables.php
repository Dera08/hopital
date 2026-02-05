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
            $table->boolean('is_visible_to_patient')->default(false)->after('status');
        });

        Schema::table('clinical_observations', function (Blueprint $table) {
            $table->boolean('is_visible_to_patient')->default(false)->after('is_critical');
        });

        Schema::table('lab_requests', function (Blueprint $table) {
            $table->boolean('is_visible_to_patient')->default(false)->after('status');
        });

        Schema::table('medical_documents', function (Blueprint $table) {
            $table->boolean('is_visible_to_patient')->default(false)->after('version');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('is_visible_to_patient');
        });

        Schema::table('clinical_observations', function (Blueprint $table) {
            $table->dropColumn('is_visible_to_patient');
        });

        Schema::table('lab_requests', function (Blueprint $table) {
            $table->dropColumn('is_visible_to_patient');
        });

        Schema::table('medical_documents', function (Blueprint $table) {
            $table->dropColumn('is_visible_to_patient');
        });
    }
};
