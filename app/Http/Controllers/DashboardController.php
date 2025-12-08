<?php

namespace App\Http\Controllers;

use App\Models\{Patient, Appointment, Admission, Room, ClinicalAlert, AuditLog, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Statistiques générales
        $stats = $this->getStats($user);
        
        // Rendez-vous du jour
        $todayAppointments = $this->getTodayAppointments($user);
        
        // Admissions actives
        $activeAdmissions = $this->getActiveAdmissions($user);
        
        // Alertes cliniques non accusées
        $clinicalAlerts = $this->getClinicalAlerts($user);
        
        return view('dashboard', compact(
            'stats',
            'todayAppointments',
            'activeAdmissions',
            'clinicalAlerts'
        ));
    }

    private function getStats($user)
    {
        $stats = [];
        
        // Patients actifs
        $stats['active_patients'] = Patient::where('is_active', true)->count();
        
        // Rendez-vous aujourd'hui
        $stats['today_appointments'] = Appointment::whereDate('appointment_datetime', today())
            ->when($user->isDoctor(), function($query) use ($user) {
                return $query->where('doctor_id', $user->id);
            })
            ->count();
            
        $stats['pending_appointments'] = Appointment::whereDate('appointment_datetime', today())
            ->where('status', 'scheduled')
            ->when($user->isDoctor(), function($query) use ($user) {
                return $query->where('doctor_id', $user->id);
            })
            ->count();
        
        // Statistiques des lits
        $bedStats = Room::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
            
        $stats['available_beds'] = $bedStats['available'] ?? 0;
        $stats['occupied_beds'] = $bedStats['occupied'] ?? 0;
        $stats['cleaning_beds'] = $bedStats['cleaning'] ?? 0;
        $stats['total_beds'] = array_sum($bedStats);
        $stats['occupancy_rate'] = $stats['total_beds'] > 0 
            ? round(($stats['occupied_beds'] / $stats['total_beds']) * 100, 1) 
            : 0;
        
        // Alertes cliniques
        $stats['active_alerts'] = ClinicalAlert::where('is_acknowledged', false)->count();
        $stats['critical_alerts'] = ClinicalAlert::where('is_acknowledged', false)
            ->where('severity', 'critical')
            ->count();
        
        return $stats;
    }

    private function getTodayAppointments($user)
    {
        return Appointment::with(['patient', 'doctor', 'service'])
            ->whereDate('appointment_datetime', today())
            ->when($user->isDoctor(), function($query) use ($user) {
                return $query->where('doctor_id', $user->id);
            })
            ->when($user->service_id, function($query) use ($user) {
                return $query->where('service_id', $user->service_id);
            })
            ->orderBy('appointment_datetime')
            ->get();
    }

    private function getActiveAdmissions($user)
    {
        return Admission::with(['patient', 'room', 'doctor'])
            ->where('status', 'active')
            ->when($user->isDoctor(), function($query) use ($user) {
                return $query->where('doctor_id', $user->id);
            })
            ->when($user->service_id && !$user->isAdmin(), function($query) use ($user) {
                return $query->whereHas('room', function($q) use ($user) {
                    $q->where('service_id', $user->service_id);
                });
            })
            ->latest('admission_date')
            ->limit(10)
            ->get();
    }

    private function getClinicalAlerts($user)
    {
        return ClinicalAlert::with(['patient', 'triggeredBy'])
            ->where('is_acknowledged', false)
            ->when(!$user->isAdmin(), function($query) use ($user) {
                // Limiter aux patients du service de l'utilisateur
                return $query->whereHas('patient.admissions', function($q) use ($user) {
                    $q->where('status', 'active')
                      ->whereHas('room', function($r) use ($user) {
                          $r->where('service_id', $user->service_id);
                      });
                });
            })
            ->orderByRaw("FIELD(severity, 'critical', 'high', 'medium', 'low')")
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function stats(Request $request)
    {
        // API endpoint pour les statistiques dynamiques
        $period = $request->input('period', '7days');
        
        return response()->json([
            'appointments' => $this->getAppointmentStats($period),
            'admissions' => $this->getAdmissionStats($period),
            'occupancy' => $this->getOccupancyTrend($period)
        ]);
    }

    private function getAppointmentStats($period)
    {
        $days = $period === '30days' ? 30 : 7;
        
        return Appointment::selectRaw('DATE(appointment_datetime) as date, COUNT(*) as count')
            ->where('appointment_datetime', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getAdmissionStats($period)
    {
        $days = $period === '30days' ? 30 : 7;
        
        return Admission::selectRaw('DATE(admission_date) as date, COUNT(*) as count')
            ->where('admission_date', '>=', now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getOccupancyTrend($period)
    {
        // Calcul du taux d'occupation sur la période
        $days = $period === '30days' ? 30 : 7;
        
        $data = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $totalBeds = Room::where('is_active', true)->count();
            $occupiedBeds = Admission::where('status', 'active')
                ->whereDate('admission_date', '<=', $date)
                ->where(function($q) use ($date) {
                    $q->whereNull('discharge_date')
                      ->orWhereDate('discharge_date', '>=', $date);
                })
                ->count();
            
            $data[] = [
                'date' => $date,
                'rate' => $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100, 1) : 0
            ];
        }
        
        return $data;
    }

    public function auditLogs(Request $request)
    {
        // Page des logs d'audit (Admin uniquement)
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $logs = AuditLog::with('user')
            ->when($request->filled('user_id'), function($query) use ($request) {
                return $query->where('user_id', $request->user_id);
            })
            ->when($request->filled('action'), function($query) use ($request) {
                return $query->where('action', $request->action);
            })
            ->when($request->filled('resource_type'), function($query) use ($request) {
                return $query->where('resource_type', $request->resource_type);
            })
            ->when($request->filled('date_from'), function($query) use ($request) {
                return $query->whereDate('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function($query) use ($request) {
                return $query->whereDate('created_at', '<=', $request->date_to);
            })
            ->latest('created_at')
            ->paginate(50);

        $users = User::select('id', 'name', 'email')->get();
        
        return view('audit-logs.index', compact('logs', 'users'));
    }

    public function auditLogDetail(AuditLog $log)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $log->load('user');
        
        return view('audit-logs.show', compact('log'));
    }

    public function settings()
    {
        $user = auth()->user();
        
        return view('settings', compact('user'));
    }
}
