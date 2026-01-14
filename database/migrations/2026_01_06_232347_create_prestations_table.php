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
        Schema::create('prestations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Ex: "Consultation Cardiologie", "ECG", "Radio Thorax"
            $table->string('code')->unique(); // Code unique pour la facturation
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Prix unitaire
            $table->enum('category', ['consultation', 'examen', 'soins', 'medicament', 'hospitalisation'])->default('consultation');
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_payment')->default(true); // Certaines prestations peuvent être gratuites
            $table->timestamps();

            $table->index(['hospital_id', 'service_id']);
            $table->index(['category', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestations');
    }
};
