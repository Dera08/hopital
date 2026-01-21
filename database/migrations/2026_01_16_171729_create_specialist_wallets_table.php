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
        Schema::create('specialist_wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialist_id')->constrained('medecins_externes')->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0);
            $table->boolean('is_activated')->default(false);
            $table->boolean('is_blocked')->default(false);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('last_recharge_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialist_wallets');
    }
};
