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
        Schema::create('lab_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Nom du réactif ou consommable
            $table->string('unit')->default('pcs'); // Unité (kit, boîte, ml, etc.)
            $table->integer('quantity')->default(0); // Quantité actuelle
            $table->integer('min_threshold')->default(5); // Seuil d'alerte
            $table->string('batch_number')->nullable(); // Numéro de lot
            $table->date('expiry_date')->nullable(); // Date d'expiration
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['hospital_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_inventories');
    }
};
