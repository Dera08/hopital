@extends('layouts.cashier_layout')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Historique des paiements</h2>
    <div class="flex gap-2">
        <button class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-filter"></i> <span>Filtrer</span>
        </button>
        <button class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-download"></i> <span>Exporter Excel</span>
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500">
        <p class="text-gray-600 text-sm mb-2">Total encaissé</p>
        <p class="text-3xl font-bold text-green-600">450 000 F CFA</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500">
        <p class="text-gray-600 text-sm mb-2">Nombre de paiements</p>
        <p class="text-3xl font-bold text-blue-600">12</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-purple-500">
        <p class="text-gray-600 text-sm mb-2">Moyenne / Patient</p>
        <p class="text-3xl font-bold text-purple-600">37 500 F</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-green-50 border-b border-green-100">
            <tr>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Référence</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Date & Heure</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Patient</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Montant</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Méthode</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
           @foreach($payments as $payment)
<tr class="hover:bg-gray-50 border-b border-gray-100">
    <td class="px-6 py-4 font-bold text-gray-800">#{{ $payment->invoice_number }}</td>
    <td class="px-6 py-4 text-sm text-gray-600">
        {{ $payment->invoice_date->format('d/m/Y H:i') }}
    </td>
    <td class="px-6 py-4">
        <div class="font-bold text-gray-700">{{ $payment->patient->name }}</div>
    </td>
    <td class="px-6 py-4 font-black text-gray-900">
        {{ number_format($payment->total, 0, ',', ' ') }} F CFA
    </td>
    <td class="px-6 py-4">
        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-black uppercase">
            {{ $payment->payment_method ?? 'Espèces' }}
        </span>
    </td>
    <td class="px-6 py-4 text-right">
        <div class="flex gap-1 justify-end">
            {{-- Le bouton de visualisation --}}
            <a href="{{ route('cashier.invoices.show', $payment->id) }}" class="bg-blue-50 text-blue-600 px-3 py-2 rounded-lg hover:bg-blue-600 hover:text-white transition-all text-sm" title="Voir">
                <i class="fas fa-eye mr-1"></i>Voir
            </a>
            {{-- Le bouton d'impression --}}
            <a href="{{ route('cashier.invoices.print', $payment->id) }}" target="_blank" class="bg-green-50 text-green-600 px-3 py-2 rounded-lg hover:bg-green-600 hover:text-white transition-all text-sm" title="Imprimer">
                <i class="fas fa-print mr-1"></i>Imprimer
            </a>
            {{-- Le bouton PDF --}}
            <a href="{{ route('cashier.invoices.pdf', $payment->id) }}" class="bg-red-50 text-red-600 px-3 py-2 rounded-lg hover:bg-red-600 hover:text-white transition-all text-sm" title="Télécharger PDF">
                <i class="fas fa-file-pdf mr-1"></i>PDF
            </a>
        </div>
    </td>
</tr>
@endforeach
        </tbody>
    </table>
</div>
@endsection