<?php

namespace App\Http\Controllers;

use App\Models\{User, Service, AuditLog, Patient, Appointment, Admission, Invoice};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, DB};
use Carbon\Carbon;
class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:administrative,admin');
    }

    public function index()
    {
        return view('reports.index');
    }

    public function activityReport(Request $request)
    {
        $period = $request->input('period', '30days');
        $days = $period === '7days' ? 7 : 30;

        $data = [
            'appointments' => Appointment::whereDate('appointment_datetime', '>=', now()->subDays($days))
                ->selectRaw('DATE(appointment_datetime) as date, status, COUNT(*) as count')
                ->groupBy('date', 'status')
                ->get(),
            
            'admissions' => Admission::whereDate('admission_date', '>=', now()->subDays($days))
                ->selectRaw('DATE(admission_date) as date, COUNT(*) as count')
                ->groupBy('date')
                ->get(),
            
            'new_patients' => Patient::whereDate('created_at', '>=', now()->subDays($days))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->get(),
        ];

        return view('reports.activity', compact('data', 'period'));
    }

    public function occupancyReport(Request $request)
    {
        $services = Service::with(['rooms' => function($q) {
            $q->where('is_active', true);
        }])->where('is_active', true)->get();

        $occupancyData = [];

        foreach ($services as $service) {
            $total = $service->rooms->count();
            $occupied = $service->rooms->where('status', 'occupied')->count();
            $available = $service->rooms->where('status', 'available')->count();

            $occupancyData[] = [
                'service' => $service->name,
                'total' => $total,
                'occupied' => $occupied,
                'available' => $available,
                'rate' => $total > 0 ? round(($occupied / $total) * 100, 1) : 0,
            ];
        }

        return view('reports.occupancy', compact('occupancyData'));
    }

    public function financialReport(Request $request)
    {
        $period = $request->input('period', '30days');
        $days = $period === '7days' ? 7 : ($period === '30days' ? 30 : 365);

        $invoices = Invoice::whereDate('invoice_date', '>=', now()->subDays($days))
            ->selectRaw('DATE(invoice_date) as date, status, SUM(total) as amount, COUNT(*) as count')
            ->groupBy('date', 'status')
            ->get();

        $summary = [
            'total_revenue' => Invoice::where('status', 'paid')
                ->whereDate('invoice_date', '>=', now()->subDays($days))
                ->sum('total'),
            'pending_revenue' => Invoice::where('status', 'pending')
                ->whereDate('invoice_date', '>=', now()->subDays($days))
                ->sum('total'),
            'total_invoices' => Invoice::whereDate('invoice_date', '>=', now()->subDays($days))->count(),
            'paid_invoices' => Invoice::where('status', 'paid')
                ->whereDate('invoice_date', '>=', now()->subDays($days))
                ->count(),
        ];

        return view('reports.financial', compact('invoices', 'summary', 'period'));
    }

    public function appointmentsReport(Request $request)
    {
        $period = $request->input('period', '30days');
        $days = $period === '7days' ? 7 : 30;

        $appointments = Appointment::whereDate('appointment_datetime', '>=', now()->subDays($days))
            ->with(['patient', 'doctor', 'service'])
            ->get();

        $stats = [
            'total' => $appointments->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'no_show' => $appointments->where('status', 'no_show')->count(),
            'completion_rate' => $appointments->count() > 0 
                ? round(($appointments->where('status', 'completed')->count() / $appointments->count()) * 100, 1)
                : 0,
        ];

        return view('reports.appointments', compact('appointments', 'stats', 'period'));
    }
}

 