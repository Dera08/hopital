@extends('layouts.cashier_layout')

@section('content')
<div class="p-8 animate-fade-in">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-black text-gray-800 tracking-tight">Gestion des factures</h2>
            <p class="text-gray-500 font-medium">Consultez et gérez les facturations des patients.</p>
        </div>
        <button class="bg-blue-600 text-white px-6 py-3 rounded-xl shadow-lg shadow-blue-200 hover:bg-blue-700 flex items-center gap-2 font-bold transition-all transform hover:scale-105">
            <i class="fas fa-plus"></i> Nouvelle facture
        </button>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        {{-- IL Y A MAINTENANT 7 COLONNES --}}
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-wider">N° Facture</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-wider">Services</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-5 text-xs font-black text-gray-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($invoices as $invoice)
                        @php
                            $appointment = $invoice->admission ? $invoice->admission->appointment : null;
                            $totalPrestations = $appointment ? $appointment->prestations->sum('pivot.total') : 0;
                            $prixService = $appointment ? ($appointment->service->price ?? 0) : 0;
                            $montantTotal = ($invoice->total > 0) ? $invoice->total : ($prixService + $totalPrestations);
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            {{-- 1. N° Facture --}}
                            <td class="px-6 py-5 font-black text-gray-800 text-sm italic">
                                {{ $invoice->invoice_number }}
                            </td>

                            {{-- 2. Date (AJOUTÉ POUR L'ALIGNEMENT) --}}
                            <td class="px-6 py-5 text-sm text-gray-600 font-bold">
                                {{ $invoice->invoice_date->format('d/m/Y') }}
                            </td>

                            {{-- 3. Patient --}}
                            <td class="px-6 py-5">
                                <span class="font-bold text-gray-700">{{ $invoice->patient ? $invoice->patient->name : 'Patient inconnu' }}</span>
                            </td>

                            {{-- 4. Services --}}
                            <td class="px-6 py-5">
                                <div class="text-xs font-bold text-gray-500">
                                    {{ $appointment ? ($appointment->service->name ?? 'Service général') : 'Service général' }}
                                    @if($appointment && $appointment->prestations->count() > 0)
                                        <span class="text-blue-500 block">+ {{ $appointment->prestations->count() }} prestation(s)</span>
                                    @endif
                                </div>
                            </td>

                            {{-- 5. Montant --}}
                            <td class="px-6 py-5 font-black text-gray-900 text-sm">
                                {{ number_format($montantTotal, 0, ',', ' ') }} F
                            </td>

                            {{-- 6. Statut --}}
                            <td class="px-6 py-5">
                                <span class="px-3 py-1.5 {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }} rounded-full text-[10px] font-black uppercase tracking-tighter">
                                    {{ $invoice->status == 'paid' ? 'Payée' : 'En attente' }}
                                </span>
                            </td>

                            {{-- 7. Actions --}}
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('cashier.invoices.show', $invoice->id) }}" class="w-9 h-9 flex items-center justify-center text-blue-600 hover:bg-blue-100 rounded-xl transition-all" title="Détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('cashier.invoices.print', $invoice->id) }}" target="_blank" class="w-9 h-9 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-xl transition-all" title="Imprimer">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <a href="{{ route('cashier.invoices.pdf', $invoice->id) }}" class="w-9 h-9 flex items-center justify-center text-emerald-500 hover:bg-emerald-50 rounded-xl transition-all" title="PDF">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection