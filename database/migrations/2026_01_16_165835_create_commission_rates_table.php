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
        Schema::create('commission_rates', function (Blueprint $table) {
            $table->id();
            $table->string('service_type'); // consultation, chirurgie, analyse, etc.
            $table->decimal('activation_fee', 10, 2)->default(4000); // Frais d'accès fixe (4 000 FCFA)
            $table->decimal('commission_percentage', 5, 2); // Pourcentage de commission (ex: 12.5)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_rates');
    }
};
