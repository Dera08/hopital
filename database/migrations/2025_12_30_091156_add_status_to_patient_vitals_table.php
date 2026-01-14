<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
   {
    Schema::table('patient_vitals', function (Blueprint $table) {
        // 'active' par défaut, passera à 'archived' au clic
        $table->string('status')->default('active')->after('ordonnance');
    });
   }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_vitals', function (Blueprint $table) {
            //
        });
    }
};
