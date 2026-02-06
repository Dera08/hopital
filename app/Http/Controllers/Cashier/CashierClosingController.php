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

        $cashTotal = 0;
        $mobileTotal = 0;
        $insuranceTotal = 0;

        foreach ($dailyInvoices as $invoice) {
            $fullTotal = $invoice->total;
            $insurancePart = ($fullTotal * ($invoice->insurance_coverage_rate ?? 0)) / 100;
            $patientPart = $fullTotal - $insurancePart;

            $insuranceTotal += $insurancePart;

            $isCash = in_array(strtolower($invoice->payment_method), ['cash', 'espèces', 'especes']);
            $isMobile = in_array(strtolower($invoice->payment_method), ['mobile_money', 'mobile money', 'momo', 'api']);

            if ($isCash) {
                $cashTotal += $patientPart;
            } elseif ($isMobile) {
                $mobileTotal += $patientPart;
            } else {
                // If it's 100% insurance or some other method, and not explicitly cash/mobile
                // it might have been already handleded via insurancePart if it's insurance
                // but any remaining patient part that isn't cash/mobile would be "unaccounted" here
                // but usually it's one of the two if it's a co-payment.
            }
        }

        $existingTransfer = FundTransfer::where('cashier_id', $user->id)
            ->whereDate('created_at', $today)
            ->first();

        return response()->json([
            'cash_total' => (int)$cashTotal,
            'mobile_total' => (int)$mobileTotal,
            'insurance_total' => (int)$insuranceTotal,
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

        // Calculate actual cash total (Patient portion only)
        $dailyInvoices = Invoice::where('cashier_id', $user->id)
            ->whereDate('created_at', $today)
            ->where('status', 'paid')
            ->whereIn('payment_method', ['cash', 'Espèces', 'espèces'])
            ->get();

        $cashAmount = 0;
        foreach ($dailyInvoices as $inv) {
            $insurancePart = ($inv->total * ($inv->insurance_coverage_rate ?? 0)) / 100;
            $cashAmount += ($inv->total - $insurancePart);
        }

        FundTransfer::create([
            'cashier_id' => $user->id,
            'amount' => (int)$cashAmount,
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
