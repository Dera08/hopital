<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['patient', 'admission.appointment.prestations', 'admission.appointment.service'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('cashier.invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::with(['patient', 'admission.appointment.service', 'admission.appointment.prestations'])->findOrFail($id);
        return view('cashier.invoices.show', compact('invoice'));
    }

    public function print($id)
    {
        $invoice = Invoice::with(['patient', 'admission.appointment.service', 'admission.appointment.prestations', 'hospital'])->findOrFail($id);
        return view('cashier.invoices.print', compact('invoice'));
    }

    public function download($id)
    {
        $invoice = Invoice::with(['patient', 'admission.appointment.service', 'admission.appointment.prestations', 'hospital'])->findOrFail($id);
        
        // Calcul du total si non stocké en base
        $appointment = $invoice->admission?->appointment;
        $total = ($appointment?->service?->price ?? 0) + ($appointment ? $appointment->prestations->sum('pivot.total') : 0);

        $pdf = Pdf::loadView('cashier.invoices.pdf', compact('invoice', 'total'));
        return $pdf->download('Facture_'.$invoice->invoice_number.'.pdf');
    }
}