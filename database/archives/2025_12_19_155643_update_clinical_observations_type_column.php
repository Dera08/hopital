<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // N'oublie pas d'ajouter cet import !

return new class extends Migration
{
    public function up()
    {
        // On utilise du SQL pur pour modifier la colonne sans passer par doctrine/dbal
        DB::statement("ALTER TABLE clinical_observations MODIFY COLUMN type VARCHAR(50)");
        
        // On en profite pour rendre unit optionnel (nullable) pour éviter l'erreur de tout à l'heure
        DB::statement("ALTER TABLE clinical_observations MODIFY COLUMN unit VARCHAR(20) NULL");
    }

    public function down()
    {
        // Optionnel : revenir en arrière
        DB::statement("ALTER TABLE clinical_observations MODIFY COLUMN type VARCHAR(20)");
    }
};