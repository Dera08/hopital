<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\FundTransfer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminFinanceController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $startOfMonth = Carbon::now()->startOfMonth();

        // --- 1. GLOBAL KPIs ---
        $revenueToday = Invoice::whereDate('created_at', $today)->where('status', 'paid')->sum('total');
        $revenueYesterday = Invoice::whereDate('created_at', $yesterday)->where('status', 'paid')->sum('total');
        
        $growth = $revenueYesterday > 0 ? (($revenueToday - $revenueYesterday) / $revenueYesterday) * 100 : ($revenueToday > 0 ? 100 : 0);

        $revenueMonth = Invoice::where('created_at', '>=', $startOfMonth)->where('status', 'paid')->sum('total');
        $pendingRevenue = Invoice::where('status', 'pending')->sum('total');
        
        // --- 2. VALIDATION (Versements en Attente) ---
        $pendingTransfers = FundTransfer::where('status', 'pending')
            ->with(['cashier'])
            ->latest()
            ->get();

        // --- 3. MONITORING MOBILE MONEY (API Reconciliation) ---
        $mobileInvoicesToday = Invoice::whereDate('created_at', $today)
            ->where('status', 'paid')
            ->where(function($q) {
                $q->where('payment_method', 'like', '%mobile%')
                  ->orWhere('payment_method', 'like', '%momo%')
                  ->orWhere('payment_method', 'like', '%api%');
            })->get();

        $totalMobileToday = $mobileInvoicesToday->sum('total');
        // Logic: Compare what's in DB with what should be (simulation of external API)
        // For now, we use the DB as the source of truth, 
        // but we can add an "is_api_confirmed" flag if needed in future.
        $momoReconciliationStatus = 'balanced'; 

        // --- 4. RAPPORTS & STATS (Pilotage) ---
        // Revenue by Service
        $revenueByService = Invoice::join('services', 'invoices.service_id', '=', 'services.id')
            ->whereDate('invoices.created_at', $today)
            ->where('invoices.status', 'paid')
            ->select('services.name', DB::raw('sum(invoices.total) as total'))
            ->groupBy('services.name')
            ->orderByDesc('total')
            ->get();

        // Unpaid Invoices (Factures en attente)
        $unpaidInvoices = Invoice::where('status', 'pending')
            ->with(['patient', 'service'])
            ->latest()
            ->take(10)
            ->get();

        // Part Cash vs MoMo (Donut Chart)
        $rawRevenue = Invoice::select('payment_method', DB::raw('sum(total) as total'))
            ->whereDate('created_at', $today)
            ->where('status', 'paid')
            ->groupBy('payment_method')
            ->get();

        $revenueByMethod = [
            'cash' => 0,
            'mobile' => 0
        ];
        foreach ($rawRevenue as $item) {
            $m = strtolower($item->payment_method);
            if (str_contains($m, 'cash') || str_contains($m, 'esp')) {
                $revenueByMethod['cash'] += $item->total;
            } else {
                $revenueByMethod['mobile'] += $item->total;
            }
        }
        $revenueByMethod = collect($revenueByMethod);

        // --- 5. AUDIT & HISTORIQUE ---
        $latestInvoices = Invoice::with(['patient', 'cashier', 'service'])
            ->latest()
            ->take(15)
            ->get();

        // Flux par Caisse (Mini cards)
        $caisseStats = [
             'accueil' => $this->getCaisseStats(null, $today),
             'labo' => $this->getCaisseStats('labo', $today),
             'urgence' => $this->getCaisseStats('urgence', $today),
        ];

        return view('admin.finance.index', compact(
            'revenueToday', 
            'growth',
            'revenueMonth', 
            'pendingRevenue', 
            'pendingTransfers',
            'mobileInvoicesToday',
            'totalMobileToday',
            'momoReconciliationStatus',
            'revenueByService',
            'unpaidInvoices',
            'revenueByMethod', 
            'latestInvoices',
            'caisseStats'
        ));
    }

    public function dailyDetails(Request $request)
    {
        $today = Carbon::today();
        $method = $request->query('method');
        
        $query = Invoice::whereDate('created_at', $today)
            ->where('status', 'paid')
            ->with(['patient', 'service', 'cashier']);

        if ($method) {
            $query->where(function($q) use ($method) {
                if ($method === 'cash') {
                    $q->where('payment_method', 'cash')->orWhere('payment_method', 'Espèces')->orWhere('payment_method', 'espèces');
                } elseif ($method === 'mobile') {
                    $q->where('payment_method', 'mobile_money')->orWhere('payment_method', 'Mobile Money')->orWhere('payment_method', 'MoMo');
                } else {
                    $q->where('payment_method', $method);
                }
            });
        }

        $invoices = $query->latest()->get();

        // 1. Stats by Method (Normalized for display)
        $rawStats = Invoice::select('payment_method', DB::raw('sum(total) as total'), DB::raw('count(*) as count'))
            ->whereDate('created_at', $today)
            ->where('status', 'paid')
            ->groupBy('payment_method')
            ->get();

        $statsByMethod = [
            'cash' => ['total' => 0, 'count' => 0, 'label' => 'Espèces'],
            'mobile' => ['total' => 0, 'count' => 0, 'label' => 'Mobile Money'],
        ];

        foreach ($rawStats as $stat) {
            $m = strtolower($stat->payment_method);
            if (in_array($m, ['cash', 'espèces', 'especes'])) {
                $statsByMethod['cash']['total'] += $stat->total;
                $statsByMethod['cash']['count'] += $stat->count;
            } elseif (in_array($m, ['mobile_money', 'mobile money', 'momo'])) {
                $statsByMethod['mobile']['total'] += $stat->total;
                $statsByMethod['mobile']['count'] += $stat->count;
            }
        }

        $statsByMethod = collect($statsByMethod);

        // 2. Stats by Caisse (Accueil, Labo, Urgence)
        // We categorize based on the service's caisse_type
        $statsByCaisse = [
            'accueil' => ['total' => 0, 'cashiers' => collect()],
            'labo' => ['total' => 0, 'cashiers' => collect()],
            'urgence' => ['total' => 0, 'cashiers' => collect()],
        ];

        foreach ($invoices as $inv) {
            $type = 'accueil';
            if ($inv->service) {
                if ($inv->service->caisse_type === 'labo' || strpos(strtolower($inv->service->name), 'labo') !== false) {
                    $type = 'labo';
                } elseif ($inv->service->caisse_type === 'urgence' || strpos(strtolower($inv->service->name), 'urgence') !== false) {
                    $type = 'urgence';
                }
            }
            
            $statsByCaisse[$type]['total'] += $inv->total;
            if ($inv->cashier) {
                $statsByCaisse[$type]['cashiers']->put($inv->cashier->id, $inv->cashier->name);
            }
        }

        return view('admin.finance.daily', compact('invoices', 'statsByMethod', 'statsByCaisse', 'method'));
    }

    public function treasuryDetails()
    {
        // 1. Mobile Money (Direct API)
        $mobileInvoices = Invoice::where('status', 'paid')
            ->where(function($q) {
                $q->where('payment_method', 'mobile_money')
                  ->orWhere('payment_method', 'Mobile Money')
                  ->orWhere('payment_method', 'MoMo');
            })
            ->with(['patient', 'service'])
            ->latest()
            ->paginate(15, ['*'], 'mobile_page');

        // 2. Confirmed Cash Transfers
        $cashTransfers = FundTransfer::where('status', 'confirmed')
            ->with(['cashier'])
            ->latest()
            ->paginate(15, ['*'], 'cash_page');

        $totalMobile = Invoice::where('status', 'paid')
            ->where(function($q) {
                $q->where('payment_method', 'mobile_money')
                  ->orWhere('payment_method', 'Mobile Money')
                  ->orWhere('payment_method', 'MoMo');
            })->sum('total');

        $totalCash = FundTransfer::where('status', 'confirmed')->sum('amount');

        return view('admin.finance.treasury', compact('mobileInvoices', 'cashTransfers', 'totalMobile', 'totalCash'));
    }

    private function getCaisseStats($type, $date)
    {
        // Define scopes similar to CashierController
        $baseQuery = Invoice::whereDate('created_at', $date)
            ->where('status', 'paid')
            ->whereHas('service', function($q) use ($type) {
                if ($type === 'labo') {
                    $q->where('caisse_type', 'labo')->orWhere('name', 'like', '%Labo%');
                } elseif ($type === 'urgence') {
                    $q->where('caisse_type', 'urgence')->orWhere('name', 'like', '%Urgence%');
                } else {
                    // Accueil = Tout le reste
                    $q->where(function($sub) {
                        $sub->whereNull('caisse_type')
                            ->orWhere(function($t) {
                                $t->where('caisse_type', '!=', 'labo')
                                  ->where('caisse_type', '!=', 'urgence');
                            });
                    })
                    ->where('name', 'not like', '%Labo%')
                    ->where('name', 'not like', '%Urgence%');
                }
            });

        $total = (clone $baseQuery)->sum('total');
        $count = (clone $baseQuery)->count();
        
        // Handle localized and technical strings
        $cash = (clone $baseQuery)->where(function($q) {
            $q->where('payment_method', 'cash')
              ->orWhere('payment_method', 'Espèces')
              ->orWhere('payment_method', 'espèces');
        })->sum('total');
        
        $mobile = (clone $baseQuery)->where(function($q) {
            $q->where('payment_method', 'mobile_money')
              ->orWhere('payment_method', 'Mobile Money')
              ->orWhere('payment_method', 'MoMo');
        })->sum('total');

        // Active Cashiers in this scope
        $cashierIds = (clone $baseQuery)->whereNotNull('cashier_id')->pluck('cashier_id')->unique();
        $activeCashiers = \App\Models\User::whereIn('id', $cashierIds)->get();

        return [
            'total' => $total,
            'count' => $count,
            'cash' => $cash,
            'mobile' => $mobile,
            'active_cashiers' => $activeCashiers
        ];
    }

    public function confirmTransfer(Request $request, $id)
    {
        $transfer = FundTransfer::findOrFail($id);
        $received = $request->input('received_amount', $transfer->amount);
        $gap = $received - $transfer->amount;

        $transfer->update([
            'status' => 'confirmed',
            'received_amount' => $received,
            'gap_amount' => $gap,
            'admin_id' => Auth::id(),
            'validated_at' => now()
        ]);

        $message = 'Versement confirmé avec succès.';
        if ($gap != 0) {
            $message .= " Attention : un écart de " . number_format($gap, 0, ',', ' ') . " FCFA a été enregistré.";
        }

        return redirect()->back()->with('success', $message);
    }

    public function exportInvoices(Request $request)
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=journal_transactions_" . now()->format('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $invoices = Invoice::with(['patient', 'cashier', 'service'])
            ->latest()
            ->get();

        $callback = function() use($invoices) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Heure', 'Facture', 'Service', 'Patient', 'Montant', 'Methode', 'Caissiere']);

            foreach ($invoices as $inv) {
                fputcsv($file, [
                    $inv->created_at->format('d/m/Y'),
                    $inv->created_at->format('H:i'),
                    $inv->invoice_number,
                    $inv->service->name ?? 'Général',
                    $inv->patient->name ?? 'Patient',
                    $inv->total,
                    $inv->payment_method,
                    $inv->cashier->name ?? 'Système'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
