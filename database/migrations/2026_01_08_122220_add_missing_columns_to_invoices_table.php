<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('appointment_id')->nullable()->constrained()->nullOnDelete()->after('patient_id');
            $table->date('invoice_date')->nullable()->after('appointment_id');
            $table->date('due_date')->nullable()->after('invoice_date');
            $table->decimal('subtotal', 10, 2)->default(0)->after('due_date');
            $table->decimal('tax', 10, 2)->default(0)->after('subtotal');
            
            // On remplace 'draft' par 'pending' comme statut par défaut
            $table->enum('status', ['pending', 'paid', 'draft', 'sent', 'overdue', 'cancelled'])
                  ->default('pending')
                  ->after('tax');

            $table->timestamp('paid_at')->nullable()->after('status');
            $table->string('payment_method')->nullable()->after('paid_at');
            $table->text('notes')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['appointment_id', 'invoice_date', 'due_date', 'subtotal', 'tax', 'status', 'paid_at', 'payment_method', 'notes']);
        });
    }
};