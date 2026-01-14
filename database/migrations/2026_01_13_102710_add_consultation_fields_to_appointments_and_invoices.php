<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('appointments', function (Blueprint $table) {
        if (!Schema::hasColumn('appointments', 'consultation_type')) {
            $table->enum('consultation_type', ['hospital', 'home'])->default('hospital')->after('status');
        }
        if (!Schema::hasColumn('appointments', 'home_address')) {
            $table->text('home_address')->nullable()->after('consultation_type');
        }
    });

    Schema::table('invoices', function (Blueprint $table) {
        if (!Schema::hasColumn('invoices', 'appointment_id')) {
            $table->foreignId('appointment_id')->nullable()->after('patient_id')->constrained()->onDelete('set null');
        }
    });
}

public function down()
{
    Schema::table('appointments', function (Blueprint $table) {
        $table->dropColumn(['consultation_type', 'home_address']);
    });

    Schema::table('invoices', function (Blueprint $table) {
        $table->dropForeign(['appointment_id']);
        $table->dropColumn('appointment_id');
    });
}
};
