<?php

namespace App\Http\Controllers;

use App\Models\{User, Service, AuditLog, Patient, Appointment, Admission, Invoice};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, DB};
use Carbon\Carbon;

// ============ USER CONTROLLER ============
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrative,admin');
    }

    public function index(Request $request)
    {
        $query = User::with('service');

        // Filtres
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);
        $services = Service::where('is_active', true)->get();

        return view('users.index', compact('users', 'services'));
    }

    public function create()
    {
        $services = Service::where('is_active', true)->get();
        return view('users.create', compact('services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,doctor,nurse,administrative',
            'service_id' => 'nullable|exists:services,id',
            'phone' => 'nullable|string|max:20',
            'registration_number' => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            $validated['password'] = Hash::make($validated['password']);
            $validated['is_active'] = true;

            $user = User::create($validated);

            AuditLog::log('create', 'User', $user->id, [
                'description' => 'Création d\'un compte utilisateur',
            ]);

            DB::commit();

            return redirect()->route('users.index')
                           ->with('success', 'Utilisateur créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la création.']);
        }
    }

    public function show(User $user)
    {
        $user->load('service');

        // Statistiques de l'utilisateur
        $stats = [];
        
        if ($user->isDoctor()) {
            $stats['appointments'] = Appointment::where('doctor_id', $user->id)->count();
            $stats['patients'] = Admission::where('doctor_id', $user->id)
                ->distinct('patient_id')
                ->count('patient_id');
        }

        return view('users.show', compact('user', 'stats'));
    }

    public function edit(User $user)
    {
        $services = Service::where('is_active', true)->get();
        return view('users.edit', compact('user', 'services'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,doctor,nurse,administrative',
            'service_id' => 'nullable|exists:services,id',
            'phone' => 'nullable|string|max:20',
            'registration_number' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::beginTransaction();
        try {
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $oldData = $user->toArray();
            $user->update($validated);

            AuditLog::log('update', 'User', $user->id, [
                'description' => 'Modification d\'un compte utilisateur',
                'old' => $oldData,
                'new' => $user->toArray()
            ]);

            DB::commit();

            return redirect()->route('users.show', $user)
                           ->with('success', 'Utilisateur mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function toggleStatus(User $user)
    {
        DB::beginTransaction();
        try {
            $user->update(['is_active' => !$user->is_active]);

            AuditLog::log('update', 'User', $user->id, [
                'description' => $user->is_active ? 'Activation du compte' : 'Désactivation du compte',
            ]);

            DB::commit();

            $message = $user->is_active ? 'Utilisateur activé.' : 'Utilisateur désactivé.';
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors du changement de statut.']);
        }
    }

    public function enableMfa(Request $request)
    {
        // Activer le MFA pour l'utilisateur connecté
        // Implémentation simplifiée
        auth()->user()->update(['mfa_enabled' => true]);
        
        return back()->with('success', 'Authentification à deux facteurs activée.');
    }

    public function disableMfa(Request $request)
    {
        auth()->user()->update(['mfa_enabled' => false]);
        
        return back()->with('success', 'Authentification à deux facteurs désactivée.');
    }
}
 