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
        Schema::create('transaction_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('source_type', ['hospital', 'specialist']);
            $table->unsignedBigInteger('source_id');
            $table->decimal('amount', 15, 2);
            $table->decimal('fee_applied', 5, 2)->default(0);
            $table->decimal('net_income', 15, 2);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['source_type', 'source_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_logs');
    }
};
