<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Patient;
use App\Models\WalkInConsultation;
use App\Models\Service;
use App\Models\Prestation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashierController extends Controller
{
    /**
     * Tableau de bord avec statistiques
     */
    public function dashboard()
    {
        $hospitalId = auth()->user()->hospital_id;

        $pendingPayments = Appointment::where('hospital_id', $hospitalId)
            ->where('status', 'pending_payment')
            ->with(['patient', 'service', 'doctor', 'prestations'])
            ->orderBy('appointment_datetime')
            ->get();

        $recentPayments = Appointment::where('hospital_id', $hospitalId)
            ->where('status', 'paid')
            ->where('updated_at', '>=', now()->subDays(30))
            ->with(['patient', 'service', 'doctor'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        $todayStats = [
            'pending' => $pendingPayments->count(),
            'paid_today' => Appointment::where('hospital_id', $hospitalId)
                ->where('status', 'paid')
                ->whereDate('updated_at', today())
                ->count(),
            'total_revenue_today' => Appointment::where('hospital_id', $hospitalId)
                ->where('status', 'paid')
                ->whereDate('updated_at', today())
                ->with('prestations')
                ->get()
                ->sum(fn($apt) => $apt->prestations->sum('pivot.total'))
        ];

        return view('cashier.dashboard', compact('pendingPayments', 'recentPayments', 'todayStats'));
    }

    /**
     * Liste de tous les rendez-vous pour la caisse
     */
    public function appointments()
    {
        $hospitalId = auth()->user()->hospital_id;
        $appointments = Appointment::where('hospital_id', $hospitalId)
            ->with(['patient', 'service', 'prestations', 'invoices'])
            ->orderBy('appointment_datetime', 'desc')
            ->get();

        return view('cashier.appointments', compact('appointments'));
    }

    /**
     * Validation du paiement et génération de facture
     */
    public function validatePayment(Request $request, Appointment $appointment)
{
    if ($appointment->hospital_id !== auth()->user()->hospital_id) {
        abort(403);
    }

    DB::beginTransaction();
    try {
        // 1. Récupérer l'admission associée au rendez-vous
        // Important pour la relation 'admission_id' que nous avons ajoutée
        $admission = \App\Models\Admission::where('appointment_id', $appointment->id)->first();

        // 2. Calcul des montants
        $servicePrice = $appointment->service->price ?? 0; // Prix de la consultation
        $prestations = $appointment->prestations;
        $subtotalPrestations = $prestations->sum('pivot.total');
        
        $subtotal = $servicePrice + $subtotalPrestations;
        $tax = $subtotal * 0.18; // TVA 18%
        $total = $subtotal + $tax;

        // 3. Créer la facture avec le lien vers l'admission
        $invoice = Invoice::create([
            'hospital_id' => $appointment->hospital_id,
            'invoice_number' => 'INV-' . now()->format('ymd') . '-' . str_pad($appointment->id, 4, '0', STR_PAD_LEFT),
            'patient_id' => $appointment->patient_id,
            'appointment_id' => $appointment->id,
            'admission_id' => $admission ? $admission->id : null, // Liaison corrigée
            'invoice_date' => now(),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'status' => 'paid',
            'payment_method' => $request->payment_method ?? 'Espèces',
            'paid_at' => now(),
        ]);

        // 4. Créer l'item pour le service principal (Consultation)
        if ($servicePrice > 0) {
            InvoiceItem::create([
                'hospital_id' => $appointment->hospital_id,
                'invoice_id' => $invoice->id,
                'description' => "Consultation : " . $appointment->service->name,
                'quantity' => 1,
                'unit_price' => $servicePrice,
                'total' => $servicePrice,
            ]);
        }

        // 5. Créer les items pour les prestations additionnelles
        foreach ($prestations as $prestation) {
            InvoiceItem::create([
                'hospital_id' => $appointment->hospital_id,
                'invoice_id' => $invoice->id,
                'description' => $prestation->name,
                'quantity' => $prestation->pivot->quantity,
                'unit_price' => $prestation->pivot->unit_price,
                'total' => $prestation->pivot->total,
            ]);
        }

        // 6. Mettre à jour le statut du rendez-vous
        $appointment->update(['status' => 'paid']);

        DB::commit();
        return back()->with('success', 'Paiement encaissé et facture générée !');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Échec de la transaction : ' . $e->getMessage()]);
    }
}

    /**
     * Pages restantes pour vos routes
     */
  public function payments()
{
    $hospitalId = auth()->user()->hospital_id;

    $payments = Invoice::where('hospital_id', $hospitalId)
        ->where('status', 'paid')
        ->with([
            'patient',
            'appointment.service',
            'appointment.prestations',
            'admission'
        ])
        ->latest()
        ->paginate(15);

    return view('cashier.payments', compact('payments'));
}

    public function invoices() {
        $invoices = Invoice::where('hospital_id', auth()->user()->hospital_id)
            ->with(['patient', 'admission.appointment.service', 'admission.appointment.prestations'])
            ->latest()
            ->paginate(15);
        return view('cashier.invoices', compact('invoices'));
    }

    public function patients() {
        $patients = Patient::where('hospital_id', auth()->user()->hospital_id)
            ->latest()
            ->paginate(20);
        return view('cashier.patients', compact('patients'));
    }

    public function settings() {
        return view('cashier.settings');
    }

    public function rejectPayment(Appointment $appointment) {
        $appointment->update(['status' => 'cancelled']);
        return back()->with('success', 'Rendez-vous annulé.');
    }

    public function showInvoice(Invoice $invoice) {
        if ($invoice->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }

        $invoice->load(['patient', 'items', 'appointment.service', 'appointment.prestations', 'admission']);

        return view('cashier.invoice_show', compact('invoice'));
    }

    public function printInvoice(Invoice $invoice) {
        if ($invoice->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }

        $invoice->load(['patient', 'items', 'appointment.service', 'appointment.prestations', 'admission', 'hospital']);

        return view('cashier.invoices.print', compact('invoice'));
    }

    public function downloadInvoice(Invoice $invoice) {
        if ($invoice->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }

        $invoice->load(['patient', 'items', 'appointment.service', 'appointment.prestations', 'admission', 'hospital']);

        // Calcul du total si non stocké en base
        $appointment = $invoice->appointment;
        $total = ($appointment?->service?->price ?? 0) + ($appointment ? $appointment->prestations->sum('pivot.total') : 0);

        // For now, return HTML view instead of PDF due to package installation issues
        return response()->view('cashier.invoices.pdf', compact('invoice', 'total'))
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="Facture_'.$invoice->invoice_number.'.html"');
    }

    /**
     * Liste des consultations sans rendez-vous
     */
    public function walkInConsultations()
    {
        $hospitalId = auth()->user()->hospital_id;

        $walkInConsultations = WalkInConsultation::where('hospital_id', $hospitalId)
            ->with(['patient', 'service', 'prestations'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $services = Service::where('hospital_id', $hospitalId)->get();
        $prestations = Prestation::where('hospital_id', $hospitalId)->get();

        return view('cashier.walk-in.index', compact('walkInConsultations', 'services', 'prestations'));
    }

    /**
     * Créer une nouvelle consultation sans rendez-vous
     */
    public function createWalkInConsultation(Request $request)
    {
        $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'required|string|max:20',
            'patient_email' => 'nullable|email',
            'service_id' => 'required|exists:services,id',
            'prestation_ids' => 'nullable|array',
            'prestation_ids.*' => 'exists:prestations,id',
        ]);

        $hospitalId = auth()->user()->hospital_id;

        // Créer ou récupérer le patient
        $patient = Patient::firstOrCreate(
            ['phone' => $request->patient_phone, 'hospital_id' => $hospitalId],
            [
                'name' => $request->patient_name,
                'email' => $request->patient_email,
                'hospital_id' => $hospitalId,
            ]
        );

        // Créer la consultation sans rendez-vous
        $consultation = WalkInConsultation::create([
            'hospital_id' => $hospitalId,
            'patient_id' => $patient->id,
            'service_id' => $request->service_id,
            'status' => 'pending_payment',
            'consultation_datetime' => now(),
        ]);

        // Attacher les prestations si fournies
        if ($request->prestation_ids) {
            foreach ($request->prestation_ids as $prestationId) {
                $prestation = Prestation::find($prestationId);
                if ($prestation) {
                    $consultation->prestations()->attach($prestationId, [
                        'quantity' => 1,
                        'unit_price' => $prestation->price,
                        'total' => $prestation->price,
                    ]);
                }
            }
        }

        return redirect()->route('cashier.walk-in.index')->with('success', 'Consultation sans rendez-vous créée avec succès.');
    }

    /**
     * Valider le paiement d'une consultation sans rendez-vous
     */
    public function validateWalkInPayment(Request $request, WalkInConsultation $consultation)
    {
        if ($consultation->hospital_id !== auth()->user()->hospital_id) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Calcul des montants
            $servicePrice = $consultation->service->price ?? 0;
            $prestations = $consultation->prestations;
            $subtotalPrestations = $prestations->sum('pivot.total');

            $subtotal = $servicePrice + $subtotalPrestations;
            $tax = $subtotal * 0.18; // TVA 18%
            $total = $subtotal + $tax;

            // Créer la facture
            $invoice = Invoice::create([
                'hospital_id' => $consultation->hospital_id,
                'invoice_number' => 'WALK-' . now()->format('ymd') . '-' . str_pad($consultation->id, 4, '0', STR_PAD_LEFT),
                'patient_id' => $consultation->patient_id,
                'walk_in_consultation_id' => $consultation->id,
                'invoice_date' => now(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'status' => 'paid',
                'payment_method' => $request->payment_method ?? 'Espèces',
                'paid_at' => now(),
            ]);

            // Créer l'item pour le service principal
            if ($servicePrice > 0) {
                InvoiceItem::create([
                    'hospital_id' => $consultation->hospital_id,
                    'invoice_id' => $invoice->id,
                    'description' => "Consultation sans RDV : " . $consultation->service->name,
                    'quantity' => 1,
                    'unit_price' => $servicePrice,
                    'total' => $servicePrice,
                ]);
            }

            // Créer les items pour les prestations
            foreach ($prestations as $prestation) {
                InvoiceItem::create([
                    'hospital_id' => $consultation->hospital_id,
                    'invoice_id' => $invoice->id,
                    'description' => $prestation->name,
                    'quantity' => $prestation->pivot->quantity,
                    'unit_price' => $prestation->pivot->unit_price,
                    'total' => $prestation->pivot->total,
                ]);
            }

            // Mettre à jour le statut de la consultation
            $consultation->update(['status' => 'paid']);

            DB::commit();
            return back()->with('success', 'Paiement validé et facture générée !');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Échec de la transaction : ' . $e->getMessage()]);
        }
    }
}
