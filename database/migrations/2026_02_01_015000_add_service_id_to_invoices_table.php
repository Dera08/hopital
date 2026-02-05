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
            $table->unsignedBigInteger('service_id')->nullable()->after('hospital_id');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
        });

        // Optional: Backfill existing invoices
        // For existing invoices, we can try to infer service_id from appointments
        DB::statement("UPDATE invoices i 
            JOIN appointments a ON i.appointment_id = a.id 
            SET i.service_id = a.service_id 
            WHERE i.appointment_id IS NOT NULL AND i.service_id IS NULL");
            
        DB::statement("UPDATE invoices i 
            JOIN lab_requests l ON i.lab_request_id = l.id 
            SET i.service_id = l.service_id 
            WHERE i.lab_request_id IS NOT NULL AND i.service_id IS NULL");
            
        DB::statement("UPDATE invoices i 
            JOIN walk_in_consultations w ON i.walk_in_consultation_id = w.id 
            SET i.service_id = w.service_id 
            WHERE i.walk_in_consultation_id IS NOT NULL AND i.service_id IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');
        });
    }
};
