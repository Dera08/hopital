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
    Schema::table('appointments', function (Blueprint $table) {
        $table->integer('duration')->default(30)->after('appointment_datetime');
        $table->enum('type', ['consultation', 'follow_up', 'emergency', 'routine_checkup'])->default('consultation')->after('duration');
        
        // RETIRE LA LIGNE hospital_id ICI car elle existe déjà !
        
        $table->boolean('is_recurring')->default(false)->after('type');
        $table->string('recurrence_pattern')->nullable()->after('is_recurring');
        $table->text('reason')->nullable()->after('recurrence_pattern');
        $table->text('notes')->nullable()->after('reason');
        $table->boolean('reminder_sent')->default(false)->after('notes');
        $table->timestamp('reminder_sent_at')->nullable()->after('reminder_sent');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'reminder_sent_at',
                'reminder_sent',
                'notes',
                'reason',
                'recurrence_pattern',
                'is_recurring',
                'type',
                'duration'
            ]);
        });
    }
};
