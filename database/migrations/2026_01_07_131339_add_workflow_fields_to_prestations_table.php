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
        Schema::table('prestations', function (Blueprint $table) {
            $table->enum('payment_timing', ['before', 'after', 'upon_completion'])->default('after')->after('requires_payment');
            $table->boolean('requires_approval')->default(false)->after('payment_timing');
            $table->integer('approval_level')->nullable()->after('requires_approval'); // Niveau d'approbation requis (1=infirmier, 2=médecin, 3=chef de service)
            $table->boolean('is_emergency')->default(false)->after('approval_level'); // Prestation d'urgence
            $table->integer('estimated_duration')->nullable()->after('is_emergency'); // Durée estimée en minutes
            $table->json('required_equipment')->nullable()->after('estimated_duration'); // Équipement requis
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium')->after('required_equipment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestations', function (Blueprint $table) {
            $table->dropColumn([
                'payment_timing',
                'requires_approval',
                'approval_level',
                'is_emergency',
                'estimated_duration',
                'required_equipment',
                'priority'
            ]);
        });
    }
};
