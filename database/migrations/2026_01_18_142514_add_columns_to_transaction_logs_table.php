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
        if (!Schema::hasTable('transaction_logs')) {
            // Create the table if it doesn't exist
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
        } else {
            // Add columns if table exists but is missing columns
            Schema::table('transaction_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('transaction_logs', 'source_type')) {
                    $table->enum('source_type', ['hospital', 'specialist'])->after('id');
                }
                if (!Schema::hasColumn('transaction_logs', 'source_id')) {
                    $table->unsignedBigInteger('source_id')->after('source_type');
                }
                if (!Schema::hasColumn('transaction_logs', 'amount')) {
                    $table->decimal('amount', 15, 2)->after('source_id');
                }
                if (!Schema::hasColumn('transaction_logs', 'fee_applied')) {
                    $table->decimal('fee_applied', 5, 2)->default(0)->after('amount');
                }
                if (!Schema::hasColumn('transaction_logs', 'net_income')) {
                    $table->decimal('net_income', 15, 2)->after('fee_applied');
                }
                if (!Schema::hasColumn('transaction_logs', 'description')) {
                    $table->text('description')->nullable()->after('net_income');
                }

                // Index already exists from original migration, no need to add again
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only drop the table if it was created by this migration
        // If it existed before, just remove the added columns
        if (Schema::hasTable('transaction_logs')) {
            // Check if table has only basic columns (id, timestamps) - meaning it was created by this migration
            $columns = Schema::getColumnListing('transaction_logs');
            if (count($columns) <= 3) { // id, created_at, updated_at
                Schema::dropIfExists('transaction_logs');
            } else {
                // Remove added columns
                Schema::table('transaction_logs', function (Blueprint $table) {
                    $table->dropIndex(['source_type', 'source_id']);
                    if (Schema::hasColumn('transaction_logs', 'source_type')) {
                        $table->dropColumn(['source_type', 'source_id', 'amount', 'fee_applied', 'net_income', 'description']);
                    }
                });
            }
        }
    }
};
