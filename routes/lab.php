<?php

use App\Http\Controllers\LabRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'active_user', 'role:lab_technician'])->group(function () {
    Route::get('/lab/dashboard', [LabRequestController::class, 'index'])->name('lab.dashboard');
    Route::get('/lab/worklist', [LabRequestController::class, 'worklist'])->name('lab.worklist');
    Route::get('/lab/history', [LabRequestController::class, 'history'])->name('lab.history');
    Route::get('/lab/inventory', [LabRequestController::class, 'inventory'])->name('lab.inventory.index');
    
    // Actions
    Route::post('/lab/requests/{lab_request}/status', [LabRequestController::class, 'updateStatus'])->name('lab.requests.status');
    Route::post('/lab/requests/{lab_request}/result', [LabRequestController::class, 'submitResult'])->name('lab.requests.result');
});

Route::middleware(['auth', 'active_user', 'role:doctor_lab'])->group(function () {
    Route::get('/lab/biologist/dashboard', [LabRequestController::class, 'biologistDashboard'])->name('lab.biologist.dashboard');
    Route::get('/lab/biologist/validation', [LabRequestController::class, 'validationList'])->name('lab.biologist.validation');
    Route::get('/lab/biologist/stats', [LabRequestController::class, 'biologistStats'])->name('lab.biologist.stats');
    Route::post('/lab/requests/{lab_request}/validate', [LabRequestController::class, 'validateResult'])->name('lab.requests.validate');
});
