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
        Schema::table('super_admins', function (Blueprint $table) {
            if (!Schema::hasColumn('super_admins', 'wallet_balance')) {
                $table->decimal('wallet_balance', 15, 2)->default(0)->after('access_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('super_admins', function (Blueprint $table) {
            if (Schema::hasColumn('super_admins', 'wallet_balance')) {
                $table->dropColumn('wallet_balance');
            }
        });
    }
};
