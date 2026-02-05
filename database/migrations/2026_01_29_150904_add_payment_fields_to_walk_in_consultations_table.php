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
        Schema::table('walk_in_consultations', function (Blueprint $table) {
            $table->unsignedBigInteger('patient_id')->nullable()->after('hospital_id');
            $table->unsignedBigInteger('service_id')->nullable()->after('patient_id');
            $table->timestamp('consultation_datetime')->nullable()->after('service_id');
            $table->string('status')->default('pending_payment')->after('consultation_datetime'); // pending_payment, paid, cancelled
            $table->string('payment_transaction_id')->nullable()->after('status');
            $table->string('payment_method')->nullable()->after('payment_transaction_id'); // cash, mobile_money
            $table->string('payment_operator')->nullable()->after('payment_method'); // mtn, orange, moov
            
            // Foreign keys
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('walk_in_consultations', function (Blueprint $table) {
            $table->dropForeign(['patient_id']);
            $table->dropForeign(['service_id']);
            $table->dropColumn([
                'patient_id', 
                'service_id', 
                'consultation_datetime', 
                'status',
                'payment_transaction_id', 
                'payment_method', 
                'payment_operator'
            ]);
        });
    }
};
