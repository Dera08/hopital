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
        Schema::create('fund_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cashier_id'); // Sender
            $table->unsignedBigInteger('admin_id')->nullable(); // Receiver (Validator)
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['cash', 'check', 'other'])->default('cash');
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('transfer_date')->nullable(); // Date of physical transfer
            $table->timestamp('validated_at')->nullable(); // Date of admin confirmation
            $table->timestamps();

            $table->foreign('cashier_id')->references('id')->on('users');
            $table->foreign('admin_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fund_transfers');
    }
};
