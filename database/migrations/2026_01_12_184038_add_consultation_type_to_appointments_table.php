<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
{
    Schema::table('appointments', function (Blueprint $table) {
        $table->enum('consultation_type', ['hospital', 'home'])->default('hospital')->after('status');
        $table->text('home_address')->nullable()->after('consultation_type');
    });
}

public function down()
{
    Schema::table('appointments', function (Blueprint $table) {
        $table->dropColumn(['consultation_type', 'home_address']);
    });
}
};
