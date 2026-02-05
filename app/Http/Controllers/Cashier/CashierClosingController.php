<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\FundTransfer;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CashierClosingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();

        // 1. Calculate Daily Totals for CURRENT CASHIER
        $dailyInvoices = Invoice::where('cashier_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('status', 'paid')
            ->get();

        $mobileMoneyTotal = $dailyInvoices->where('payment_method', 'mobile_money')->sum('total');
        $cashTotal = $dailyInvoices->where('payment_method', 'cash')->sum('total');
        $otherTotal = $dailyInvoices->whereNotIn('payment_method', ['mobile_money', 'cash'])->sum('total');

        // 2. Check if a transfer request already exists for today
        $existingTransfer = FundTransfer::where('cashier_id', $user->id)
            ->whereDate('created_at', $today)
            ->first();

        // 3. Recent transfers history
        $recentTransfers = FundTransfer::where('cashier_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('cashier.closing', compact('mobileMoneyTotal', 'cashTotal', 'otherTotal', 'existingTransfer', 'recentTransfers'));
    }

    public function getTotals()
    {
        $user = Auth::user();
        $today = Carbon::today();

        $dailyInvoices = Invoice::where('cashier_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('status', 'paid')
            ->get();

        $cashTotal = $dailyInvoices->whereIn('payment_method', ['cash', 'Espèces', 'espèces'])->sum('total');
        $mobileTotal = $dailyInvoices->whereIn('payment_method', ['mobile_money', 'Mobile Money', 'MoMo', 'api'])->sum('total');

        $existingTransfer = FundTransfer::where('cashier_id', $user->id)
            ->whereDate('created_at', $today)
            ->first();

        return response()->json([
            'cash_total' => $cashTotal,
            'mobile_total' => $mobileTotal,
            'is_closed' => !!$existingTransfer,
            'status' => $existingTransfer ? $existingTransfer->status : null
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        // Prevent duplicate closing
        $existing = FundTransfer::where('cashier_id', $user->id)
            ->whereDate('created_at', $today)
            ->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Caisse déjà clôturée pour aujourd\'hui.']);
        }

        // Calculate actual cash total for security
        $cashAmount = Invoice::where('cashier_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('status', 'paid')
            ->whereIn('payment_method', ['cash', 'Espèces', 'espèces'])
            ->sum('total');

        FundTransfer::create([
            'cashier_id' => $user->id,
            'amount' => $cashAmount,
            'type' => 'cash',
            'status' => 'pending',
            'transfer_date' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Demande de versement envoyée à l\'Admin. Veuillez lui remettre le cash.']);
        }

        return redirect()->back()->with('success', 'Demande de versement transmise à l\'Admin.');
    }
}
