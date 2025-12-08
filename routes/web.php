 <?php

use App\Http\Controllers\{
    DashboardController,
    PatientController,
    AppointmentController,
    AdmissionController,
    PrescriptionController,
    MedicalRecordController,
    UserController,
    RoomController,
    InvoiceController,
    PortalController,
    ReportController
};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - HospitSIS
|--------------------------------------------------------------------------
*/

// ============ PAGE D'ACCUEIL PUBLIQUE ============
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// ============ ROUTES D'AUTHENTIFICATION (STAFF) ============
// On utilise Auth::routes() SANS les routes login/logout/register
Auth::routes([
    'register' => false,  // Désactivé (seul l'admin crée des comptes)
    'verify' => false,    // Optionnel
    'login' => false,     // On gère manuellement
    'logout' => false,    // On gère manuellement
]);

// Routes de connexion/déconnexion personnalisées
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (auth()->attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        // Vérifier si l'utilisateur est actif
        if (!auth()->user()->is_active) {
            auth()->logout();
            return back()->withErrors([
                'email' => 'Votre compte a été désactivé. Contactez l\'administrateur.',
            ])->onlyInput('email');
        }

        // Log de connexion
        \App\Models\AuditLog::log('login', 'User', auth()->id(), [
            'description' => 'Connexion réussie',
        ]);

        return redirect()->intended(route('dashboard'));
    }

    return back()->withErrors([
        'email' => 'Les identifiants fournis sont incorrects.',
    ])->onlyInput('email');
})->middleware('guest');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    // Log de déconnexion
    if (auth()->check()) {
        \App\Models\AuditLog::log('logout', 'User', auth()->id(), [
            'description' => 'Déconnexion',
        ]);
    }

    auth()->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout')->middleware('auth');

 // ============ PORTAIL PATIENT (Guard séparé) ============
Route::prefix('portal')->name('portal.')->group(function () {
    
    // Login patient
    Route::get('/login', function () {
        return view('portal.login');
    })->name('login')->middleware('guest:patients');

    Route::post('/login', function (\Illuminate\Http\Request $request) {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Tentative de connexion avec le guard patients
        if (auth()->guard('patients')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Vérifier si le patient est actif
            if (!auth()->guard('patients')->user()->is_active) {
                auth()->guard('patients')->logout();
                return back()->withErrors([
                    'email' => 'Votre compte a été désactivé. Contactez l\'hôpital.',
                ]);
            }

            return redirect()->route('portal.dashboard');
        }

        return back()->withErrors([
            'email' => 'Les identifiants fournis sont incorrects.',
        ])->onlyInput('email');
    })->middleware('guest:patients');

    Route::post('/logout', function (\Illuminate\Http\Request $request) {
        auth()->guard('patients')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('portal.login');
    })->name('logout')->middleware('auth:patients');

    // Routes protégées du portail
    Route::middleware('auth:patients')->group(function () {
        Route::get('/dashboard', [PortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/appointments', [PortalController::class, 'appointments'])->name('appointments');
        Route::post('/appointments', [PortalController::class, 'bookAppointment'])->name('appointments.book');
        Route::delete('/appointments/{appointment}', [PortalController::class, 'cancelAppointment'])->name('appointments.cancel');
        Route::get('/documents', [PortalController::class, 'documents'])->name('documents');
        Route::get('/profile', [PortalController::class, 'profile'])->name('profile');
        Route::put('/profile', [PortalController::class, 'updateProfile'])->name('profile.update');
    });
});
// ============ ROUTES PROTÉGÉES (STAFF MÉDICAL) ============
Route::middleware(['auth', 'active_user'])->group(function () {
    
    // ============ DASHBOARD ============
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');

    // ============ GESTION DES PATIENTS ============
    Route::resource('patients', PatientController::class);
    Route::get('/patients/{patient}/medical-file', [PatientController::class, 'medicalFile'])->name('patients.medical-file');
    Route::get('/patients/search/quick', [PatientController::class, 'quickSearch'])->name('patients.quick-search');
    
    // ============ RENDEZ-VOUS ============
    Route::resource('appointments', AppointmentController::class);
    Route::post('/appointments/{appointment}/confirm', [AppointmentController::class, 'confirm'])->name('appointments.confirm');
    Route::post('/appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    Route::get('/appointments/doctor/{doctor}/availability', [AppointmentController::class, 'doctorAvailability'])->name('appointments.availability');
    
    // ============ ADMISSIONS & GESTION DES LITS ============
    Route::resource('admissions', AdmissionController::class);
    Route::post('/admissions/{admission}/discharge', [AdmissionController::class, 'discharge'])->name('admissions.discharge');
    Route::post('/admissions/{admission}/transfer', [AdmissionController::class, 'transfer'])->name('admissions.transfer');
    
    // Gestion des lits
    Route::get('/bed-management', [RoomController::class, 'bedManagement'])->name('rooms.bed-management');
    Route::post('/rooms/{room}/assign', [RoomController::class, 'assignBed'])->name('rooms.assign');
    Route::post('/rooms/{room}/release', [RoomController::class, 'releaseBed'])->name('rooms.release');
    Route::resource('rooms', RoomController::class)->except(['index']);
    
    // ============ DOSSIER MÉDICAL (DPI) - MÉDECINS & INFIRMIERS ============
    Route::middleware('role:doctor,nurse,admin')->group(function () {
        
        // Dossiers médicaux
        Route::get('/patients/{patient}/records', [MedicalRecordController::class, 'index'])->name('medical-records.index');
        Route::post('/patients/{patient}/records', [MedicalRecordController::class, 'store'])->name('medical-records.store');
        Route::get('/medical-records/{record}', [MedicalRecordController::class, 'show'])->name('medical-records.show');
        Route::put('/medical-records/{record}', [MedicalRecordController::class, 'update'])->name('medical-records.update');
        Route::delete('/medical-records/{record}', [MedicalRecordController::class, 'destroy'])->name('medical-records.destroy');
        
        // Observations cliniques (Constantes vitales)
        Route::get('/patients/{patient}/observations', [MedicalRecordController::class, 'observations'])->name('observations.index');
        Route::post('/patients/{patient}/observations', [MedicalRecordController::class, 'storeObservation'])->name('observations.store');
        
        // Documents médicaux
        Route::post('/patients/{patient}/documents', [MedicalRecordController::class, 'uploadDocument'])->name('documents.upload');
        Route::get('/documents/{document}/download', [MedicalRecordController::class, 'downloadDocument'])->name('documents.download');
    });
    
    // ============ PRESCRIPTIONS - MÉDECINS UNIQUEMENT ============
    Route::middleware('role:doctor')->group(function () {
        Route::resource('prescriptions', PrescriptionController::class);
        Route::post('/prescriptions/{prescription}/sign', [PrescriptionController::class, 'sign'])->name('prescriptions.sign');
        Route::post('/prescriptions/{prescription}/check-allergies', [PrescriptionController::class, 'checkAllergies'])->name('prescriptions.check-allergies');
    });
    
    // Validation de documents - Médecins uniquement
    Route::middleware('role:doctor')->group(function () {
        Route::post('/documents/{document}/validate', [MedicalRecordController::class, 'validateDocument'])->name('documents.validate');
    });
    
    // ============ NOTES DE SOINS - INFIRMIERS ============
    Route::middleware('role:nurse,doctor,admin')->group(function () {
        Route::get('/patients/{patient}/nursing-notes', [MedicalRecordController::class, 'nursingNotes'])->name('nursing-notes.index');
        Route::post('/patients/{patient}/nursing-notes', [MedicalRecordController::class, 'storeNursingNote'])->name('nursing-notes.store');
    });
    
    // ============ FACTURATION - ADMINISTRATIF ============
    Route::middleware('role:administrative,admin')->group(function () {
        Route::resource('invoices', InvoiceController::class);
        Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
        Route::post('/invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
        Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
    });
    
    // ============ GESTION DES UTILISATEURS - ADMIN/ADMINISTRATIF ============
    Route::middleware('role:administrative,admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
    
    // ============ RAPPORTS & STATISTIQUES - ADMIN/ADMINISTRATIF ============
    Route::middleware('role:administrative,admin')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/activity', [ReportController::class, 'activityReport'])->name('reports.activity');
        Route::get('/reports/occupancy', [ReportController::class, 'occupancyReport'])->name('reports.occupancy');
        Route::get('/reports/financial', [ReportController::class, 'financialReport'])->name('reports.financial');
        Route::get('/reports/appointments', [ReportController::class, 'appointmentsReport'])->name('reports.appointments');
    });
    
    // ============ LOGS D'AUDIT - ADMIN UNIQUEMENT ============
    Route::middleware('role:admin')->group(function () {
        Route::get('/audit-logs', [DashboardController::class, 'auditLogs'])->name('audit-logs.index');
        Route::get('/audit-logs/{log}', [DashboardController::class, 'auditLogDetail'])->name('audit-logs.show');
    });
    
    // ============ PARAMÈTRES UTILISATEUR ============
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::post('/settings/mfa/enable', [UserController::class, 'enableMfa'])->name('settings.mfa.enable');
    Route::post('/settings/mfa/disable', [UserController::class, 'disableMfa'])->name('settings.mfa.disable');
    
    // ============ PROFIL UTILISATEUR ============
    Route::get('/profile', function () {
        return view('profile.edit', ['user' => auth()->user()]);
    })->name('profile.edit');
    
    Route::put('/profile', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        
        if (!empty($validated['password'])) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', 'Profil mis à jour avec succès.');
    })->name('profile.update');
    
    // ============ API ENDPOINTS (pour AJAX) ============
    Route::prefix('api')->name('api.')->group(function () {
        
        // Recherche rapide de patients
        Route::get('/patients/search', [PatientController::class, 'quickSearch'])->name('patients.search');
        
        // Disponibilité médecin
        Route::get('/doctors/{doctor}/availability', [AppointmentController::class, 'doctorAvailability'])->name('doctors.availability');
        
        // Statistiques temps réel
        Route::get('/dashboard/real-time-stats', [DashboardController::class, 'stats'])->name('dashboard.realtime');
        
        // Vérification des chambres disponibles
        Route::get('/rooms/available', function (\Illuminate\Http\Request $request) {
            $serviceId = $request->input('service_id');
            
            $rooms = \App\Models\Room::where('status', 'available')
                ->where('is_active', true)
                ->when($serviceId, fn($q) => $q->where('service_id', $serviceId))
                ->with('service')
                ->get();
            
            return response()->json($rooms);
        })->name('rooms.available');
    });
});

// ============ PAGES D'ERREUR PERSONNALISÉES ============
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// ============ ROUTES DE TEST (À SUPPRIMER EN PRODUCTION) ============
if (app()->environment('local')) {
    Route::get('/test-mail', function () {
        return 'Test email configuration';
    });
    
    Route::get('/test-db', function () {
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();
            return 'Connexion à la base de données OK';
        } catch (\Exception $e) {
            return 'Erreur de connexion : ' . $e->getMessage();
        }
    });
}

// ============ ROUTES DE SANTÉ (Health Check) ============
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'version' => config('hospisis.version', '1.0.0'),
    ]);
})->name('health.check');