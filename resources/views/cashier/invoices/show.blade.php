@extends('layouts.cashier_layout')

@section('content')
<div class="p-4 sm:p-8 bg-gray-50 min-h-screen">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10 no-print">
        <h2 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tight">Facture #{{ $invoice->invoice_number }}</h2>
        <div class="flex gap-3">
            <a href="{{ route('cashier.invoices.print', $invoice->id) }}" target="_blank" class="bg-blue-600 text-white px-6 py-4 rounded-2xl shadow-xl shadow-blue-200 hover:bg-blue-700 flex items-center gap-3 font-black transition-all">
                <i class="fas fa-print"></i> Imprimer
            </a>
            <a href="{{ route('cashier.invoices.index') }}" class="bg-gray-600 text-white px-6 py-4 rounded-2xl shadow-xl shadow-gray-200 hover:bg-gray-700 flex items-center gap-3 font-black transition-all">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    {{-- Corps de la facture --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
        {{-- ... Gardez tout le contenu HTML que vous avez posté ici (Informations Patient, Items, Totals) ... --}}
        
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[600px]">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-50">
                        <th class="px-6 py-4 font-bold text-xs uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 font-bold text-xs uppercase tracking-wider text-center">Quantité</th>
                        <th class="px-6 py-4 font-bold text-xs uppercase tracking-wider text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $item->description }}</td>
                        <td class="px-6 py-4 text-center font-medium text-gray-600">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">{{ number_format($item->total, 0, ',', ' ') }} F</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- Pied de page avec totaux --}}
        <div class="mt-8 border-t border-gray-200 pt-6 text-right">
            <p class="text-2xl font-black text-blue-600">TOTAL : {{ number_format($invoice->total, 0, ',', ' ') }} F CFA</p>
        </div>
    </div>
</div>
@endsection
