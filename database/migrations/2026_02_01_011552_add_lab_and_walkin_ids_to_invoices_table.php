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
        Schema::table('invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('lab_request_id')->nullable()->after('appointment_id');
            $table->unsignedBigInteger('walk_in_consultation_id')->nullable()->after('lab_request_id');
            
            $table->foreign('lab_request_id')->references('id')->on('lab_requests')->onDelete('set null');
            // Assuming walk_in_consultations is the table name based on model name
            $table->foreign('walk_in_consultation_id')->references('id')->on('walk_in_consultations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['lab_request_id']);
            $table->dropForeign(['walk_in_consultation_id']);
            $table->dropColumn(['lab_request_id', 'walk_in_consultation_id']);
        });
    }
};
