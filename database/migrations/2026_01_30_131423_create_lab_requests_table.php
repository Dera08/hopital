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
        Schema::create('lab_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_vital_id')->constrained('patient_vitals')->onDelete('cascade');
            $table->string('patient_ipu');
            $table->string('patient_name');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('service_id')->constrained()->onDelete('cascade');
            
            $table->string('test_name'); // Ex: "TDR Palu", "NFS", "Écho-cœur"
            $table->string('test_category')->default('laboratoire'); // laboratoire, imagerie, autre
            $table->text('clinical_info')->nullable(); // Informations cliniques du médecin
            
            $table->enum('status', ['pending', 'sample_received', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('lab_technician_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->text('result')->nullable(); // Résultat textuel
            $table->json('result_data')->nullable(); // Résultats structurés (JSON)
            $table->string('result_file')->nullable(); // Fichier PDF/Image du résultat
            
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('sample_received_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            $table->index(['hospital_id', 'status']);
            $table->index(['patient_ipu', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_requests');
    }
};
