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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('dosage')->nullable()->after('medication');
            $table->string('frequency')->nullable()->after('dosage');
            $table->date('start_date')->nullable()->after('frequency');
            $table->date('end_date')->nullable()->after('start_date');
            $table->text('instructions')->nullable()->after('end_date');
            $table->boolean('is_signed')->default(false)->after('instructions');
            $table->timestamp('signed_at')->nullable()->after('is_signed');
            $table->string('signature_hash')->nullable()->after('signed_at');
            $table->boolean('allergy_checked')->default(false)->after('signature_hash');
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active')->after('allergy_checked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn([
                'dosage',
                'frequency',
                'start_date',
                'end_date',
                'instructions',
                'is_signed',
                'signed_at',
                'signature_hash',
                'allergy_checked',
                'status'
            ]);
        });
    }
};
