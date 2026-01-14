<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patient_vitals', function (Blueprint $table) {
            $table->id();
            
            // Informations Patient
            $table->string('patient_name');
            $table->string('patient_ipu'); // Identifiant Patient Unique
            
            // Constantes Médicales
            $table->float('temperature')->comment('En degrés Celsius');
            $table->integer('pulse')->comment('Battements par minute');
            $table->string('blood_pressure')->comment('Format: 120/80');
            $table->float('weight')->nullable();
            
            // Détails de la transmission
            $table->string('urgency')->default('normale'); // normale, urgent, critique
            $table->text('reason'); // Motif de consultation
            $table->text('notes')->nullable(); // Observations infirmière
            
            // Relation : Qui a envoyé le dossier ?
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->timestamps(); // created_at (date d'envoi) et updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_vitals');
    }
};