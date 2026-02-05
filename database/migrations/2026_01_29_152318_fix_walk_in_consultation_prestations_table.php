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
        // Drop and recreate the pivot table with correct structure
        Schema::dropIfExists('walk_in_consultation_prestations');
        
        Schema::create('walk_in_consultation_prestations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('walk_in_consultation_id');
            $table->unsignedBigInteger('prestation_id');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('walk_in_consultation_id', 'wic_prestation_consultation_fk')
                ->references('id')
                ->on('walk_in_consultations')
                ->onDelete('cascade');
                
            $table->foreign('prestation_id', 'wic_prestation_prestation_fk')
                ->references('id')
                ->on('prestations')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walk_in_consultation_prestations');
    }
};
