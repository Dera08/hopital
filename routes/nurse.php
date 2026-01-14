<?php

use App\Http\Controllers\NurseController;
use Illuminate\Support\Facades\Route;


// Pas besoin de la route '/' ici, elle est déjà dans web.php

// On utilise les middlewares que vous avez définis dans bootstrap/app.php
Route::middleware(['auth', 'active_user', 'role:nurse'])->group(function () {
    
    // Affichage du Dashboard React
    Route::get('/nurse/dashboard', [NurseController::class, 'index'])->name('nurse.dashboard');

    // Action d'enregistrement des constantes
    Route::post('/nurse/send', [NurseController::class, 'store'])->name('nurse.send');
   Route::delete('/nurse/vital/{id}', [NurseController::class, 'destroy'])->name('nurse.vital.destroy'); 

});
 
// SUPPRIMEZ la ligne require __DIR__.'/auth.php'; car elle est déjà dans web.php