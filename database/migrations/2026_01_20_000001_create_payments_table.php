<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_ref')->unique();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency', 10)->default('XOF');
            $table->string('buyer_type')->nullable();
            $table->unsignedBigInteger('buyer_id')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->enum('status', ['pending','completed','failed'])->default('pending');
            $table->json('metadata')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
