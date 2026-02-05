@extends('layouts.cashier_layout')

@section('content')
<div class="p-4 sm:p-8 bg-gray-50 min-h-screen">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <h2 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tight">Facture #{{ $invoice->invoice_number }}</h2>
        <div class="flex gap-3">
            <a href="{{ route('cashier.invoices.print', $invoice->id) }}" target="_blank" class="bg-blue-600 text-white px-6 py-4 rounded-2xl shadow-xl shadow-blue-200 hover:bg-blue-700 flex items-center gap-3 font-black transition-all">
                <i class="fas fa-print"></i> Imprimer
            </a>
            <a href="{{ route('cashier.appointments.index') }}" class="bg-gray-600 text-white px-6 py-4 rounded-2xl shadow-xl shadow-gray-200 hover:bg-gray-700 flex items-center gap-3 font-black transition-all">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    {{-- Invoice Details --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        {{-- Header Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-xl font-black text-gray-800 mb-4">Informations Patient</h3>
                <div class="space-y-2">
                    <p class="text-gray-600"><strong>Nom:</strong> {{ $invoice->patient?->name ?? 'Patient Supprimé' }}</p>
                    <p class="text-gray-600"><strong>Téléphone:</strong> {{ $invoice->patient?->phone ?? 'N/A' }}</p>
                    <p class="text-gray-600"><strong>Email:</strong> {{ $invoice->patient?->email ?? 'N/A' }}</p>
                </div>
            </div>
            <div>
                <h3 class="text-xl font-black text-gray-800 mb-4">Détails Facture</h3>
                <div class="space-y-2">
                    <p class="text-gray-600"><strong>Numéro:</strong> {{ $invoice->invoice_number }}</p>
                    <p class="text-gray-600"><strong>Date:</strong> {{ $invoice->invoice_date->format('d/m/Y') }}</p>
                    <p class="text-gray-600"><strong>Statut:</strong>
                        <span class="px-2 py-1 rounded-lg {{ $invoice->status == 'paid' ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600' }} text-xs font-black">
                            {{ $invoice->status == 'paid' ? 'Payée' : 'En attente' }}
                        </span>
                    </p>
                    @if($invoice->paid_at)
                        <p class="text-gray-600"><strong>Payée le:</strong> {{ $invoice->paid_at->format('d/m/Y H:i') }}</p>
                        <p class="text-gray-600"><strong>Méthode:</strong> {{ $invoice->payment_method }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Service & Appointment Info --}}
        @if($invoice->appointment)
        <div class="bg-gray-50 rounded-2xl p-6 mb-8">
            <h3 class="text-lg font-black text-gray-800 mb-4">Rendez-vous Associé</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-600"><strong>Service:</strong> {{ $invoice->appointment->service->name }}</p>
                </div>
                <div>
                    <p class="text-gray-600"><strong>Date RDV:</strong> {{ $invoice->appointment->appointment_datetime->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-gray-600"><strong>Médecin:</strong> {{ $invoice->appointment->doctor->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Invoice Items --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[600px]">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-50">
                        <th class="px-6 py-4 font-bold text-xs uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 font-bold text-xs uppercase tracking-wider text-center">Quantité</th>
                        <th class="px-6 py-4 font-bold text-xs uppercase tracking-wider text-right">Prix Unit.</th>
                        <th class="px-6 py-4 font-bold text-xs uppercase tracking-wider text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $item->description }}</td>
                        <td class="px-6 py-4 text-center font-medium text-gray-600">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 text-right font-medium text-gray-600">{{ number_format($item->unit_price, 0, ',', ' ') }} F</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">{{ number_format($item->total, 0, ',', ' ') }} F</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="mt-8 border-t border-gray-200 pt-6">
            <div class="flex justify-end">
                <div class="w-full md:w-1/3 space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">Sous-total:</span>
                        <span class="font-bold text-gray-800">{{ number_format($invoice->subtotal, 0, ',', ' ') }} F</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-600">TVA (18%):</span>
                        <span class="font-bold text-gray-800">{{ number_format($invoice->tax, 0, ',', ' ') }} F</span>
                    </div>
                    <div class="flex justify-between border-t border-gray-300 pt-3">
                        <span class="font-black text-lg text-gray-800">Total:</span>
                        <span class="font-black text-lg text-gray-800">{{ number_format($invoice->total, 0, ',', ' ') }} F</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($invoice->notes)
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-2xl p-6">
            <h4 class="font-bold text-yellow-800 mb-2">Notes:</h4>
            <p class="text-yellow-700">{{ $invoice->notes }}</p>
        </div>
        @endif
    </div>
</div>


@endsection
