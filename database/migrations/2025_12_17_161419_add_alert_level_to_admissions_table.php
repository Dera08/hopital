<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            // Ajouter le niveau d'alerte et le numéro de lit
            if (!Schema::hasColumn('admissions', 'alert_level')) {
                $table->enum('alert_level', ['stable', 'warning', 'critical'])
                    ->default('stable')
                    ->after('status');
            }
            
            if (!Schema::hasColumn('admissions', 'bed_number')) {
                $table->string('bed_number')->nullable()->after('room_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('admissions', function (Blueprint $table) {
            $table->dropColumn(['alert_level', 'bed_number']);
        });
    }
};