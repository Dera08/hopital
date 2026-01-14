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
        Schema::table('prestation_packs', function (Blueprint $table) {
            if (!Schema::hasColumn('prestation_packs', 'hospital_id')) {
                $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('prestation_packs', 'name')) {
                $table->string('name');
            }
            if (!Schema::hasColumn('prestation_packs', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('prestation_packs', 'total_price')) {
                $table->decimal('total_price', 10, 2);
            }
            if (!Schema::hasColumn('prestation_packs', 'discounted_price')) {
                $table->decimal('discounted_price', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('prestation_packs', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestation_packs', function (Blueprint $table) {
            $table->dropForeign(['hospital_id']);
            $table->dropColumn(['hospital_id', 'name', 'description', 'total_price', 'discounted_price', 'is_active']);
        });
    }
};
