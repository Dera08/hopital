@extends('layouts.app')

@section('title', 'Détails Trésorerie Hôpital')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.finance.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Trésorerie Hôpital</h1>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">Gestion des Flux Centraux</p>
            </div>
        </div>
        <div class="bg-indigo-950 px-6 py-4 rounded-2xl shadow-xl text-white">
            <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Solde Total Disponible</p>
            <h2 class="text-2xl font-black">{{ number_format($totalMobile + $totalCash, 0, ',', ' ') }} <small class="text-xs opacity-70">FCFA</small></h2>
        </div>
    </div>

    <!-- HORIZONTAL TABS (Sidebar Horizontal) -->
    <div class="flex flex-wrap gap-2 mb-8 bg-gray-100 p-1.5 rounded-2xl w-fit" x-data="{ tab: 'mobile' }">
        <button 
            @click="tab = 'mobile'" 
            :class="tab === 'mobile' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
            class="px-8 py-3 rounded-xl text-sm font-black transition-all flex items-center"
        >
            <i class="fas fa-mobile-alt mr-2" :class="tab === 'mobile' ? 'text-orange-500' : ''"></i>
            Fonds API (Mobile)
            <span class="ml-2 bg-orange-100 text-orange-600 px-2 py-0.5 rounded-lg text-[10px]">{{ number_format($totalMobile, 0, ',', ' ') }}</span>
        </button>
        <button 
            @click="tab = 'cash'" 
            :class="tab === 'cash' ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700'"
            class="px-8 py-3 rounded-xl text-sm font-black transition-all flex items-center"
        >
            <i class="fas fa-money-bill-wave mr-2" :class="tab === 'cash' ? 'text-indigo-500' : ''"></i>
            Fonds Cash (Physique)
            <span class="ml-2 bg-indigo-100 text-indigo-600 px-2 py-0.5 rounded-lg text-[10px]">{{ number_format($totalCash, 0, ',', ' ') }}</span>
        </button>

        <!-- Tab Content -->
        <div class="w-full mt-6" x-show="tab === 'mobile'" x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-95" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h3 class="font-black text-gray-900 uppercase tracking-widest text-xs flex items-center">
                        <span class="w-2 h-2 rounded-full bg-orange-500 mr-2"></span>
                        Historique des encaissements Mobile Money (API)
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date / Heure</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Référence Facture</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Caisse</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Patient & Service</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Montant (FCFA)</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Caissière</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($mobileInvoices as $invoice)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-5">
                                        <p class="text-sm font-bold text-gray-900">{{ $invoice->created_at->format('d/m/Y') }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $invoice->created_at->format('H:i:s') }}</p>
                                    </td>
                                    <td class="px-8 py-5 text-sm font-black text-blue-600">{{ $invoice->invoice_number }}</td>
                                    <td class="px-8 py-5">
                                        @php
                                            $caisseType = 'Accueil';
                                            if ($invoice->service) {
                                                if ($invoice->service->caisse_type === 'labo' || strpos(strtolower($invoice->service->name), 'labo') !== false) {
                                                    $caisseType = 'Laboratoire';
                                                } elseif ($invoice->service->caisse_type === 'urgence' || strpos(strtolower($invoice->service->name), 'urgence') !== false) {
                                                    $caisseType = 'Urgences';
                                                }
                                            }
                                        @endphp
                                        <span class="text-xs font-bold text-gray-600">{{ $caisseType }}</span>
                                    </td>
                                    <td class="px-8 py-5">
                                        <p class="text-sm font-bold text-gray-900">{{ $invoice->patient->name ?? 'Inconnu' }}</p>
                                        <p class="text-xs font-bold text-gray-400 italic">{{ $invoice->service->name ?? 'Général' }}</p>
                                    </td>
                                    <td class="px-8 py-5 text-right font-black text-gray-900 text-lg">
                                        {{ number_format($invoice->total, 0, ',', ' ') }}
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="text-xs font-bold text-gray-600">{{ $invoice->cashier->name ?? '-' }}</span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-lg uppercase">Crédité API</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 bg-gray-50 border-t border-gray-100">
                    {{ $mobileInvoices->appends(['tab' => 'mobile'])->links() }}
                </div>
            </div>
        </div>

        <div class="w-full mt-6" x-show="tab === 'cash'" x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-95" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                    <h3 class="font-black text-gray-900 uppercase tracking-widest text-xs flex items-center">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>
                        Versements Physiques Confirmés (Caisse Centrale)
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Validation de l'Admin</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Caissière Origine</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Notes / Détails</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Montant Versé</th>
                                <th class="px-8 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Preuve</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($cashTransfers as $transfer)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-8 py-5">
                                        <p class="text-sm font-bold text-gray-900">{{ $transfer->validated_at->format('d M Y') }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $transfer->validated_at->format('H:i') }}</p>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-black text-[10px] mr-3">
                                                {{ substr($transfer->cashier->name ?? '?', 0, 2) }}
                                            </div>
                                            <span class="text-sm font-black text-gray-800">{{ $transfer->cashier->name ?? 'Inconnue' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-xs font-bold text-gray-500 italic">{{ $transfer->notes ?? 'Clôture de caisse quotidienne' }}</td>
                                    <td class="px-8 py-5 text-right font-black text-gray-900 text-lg">
                                        {{ number_format($transfer->amount, 0, ',', ' ') }}
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-[10px] font-black rounded-lg uppercase">Validé par Admin</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 bg-gray-50 border-t border-gray-100">
                    {{ $cashTransfers->appends(['tab' => 'cash'])->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
