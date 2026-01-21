<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('super_admins', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('access_code'); // Le code secret qu'on a configuré
        $table->decimal('wallet_balance', 15, 2)->default(0); // Somme totale des commissions gagnées
        $table->timestamps();
    });
}
};
