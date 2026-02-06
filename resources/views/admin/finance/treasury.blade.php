@extends('layouts.admin_finance')

@section('title', 'Détails Trésorerie Hôpital')

@section('finance_content')
@php
    $realTreasury = $totalMobileAndCard + $totalConfirmedCash;
@endphp

<div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-6 animate-fade-in">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            <i class="fas fa-university text-indigo-600"></i> Trésorerie Hôpital
        </h2>
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">
             Position globale au {{ now()->translatedFormat('d F Y à H:i') }}
        </p>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full xl:w-auto">
        <!-- TREASURY LIQUIDITY -->
        <div class="bg-indigo-950 px-5 py-4 rounded-2xl shadow-xl text-white xl:min-w-[240px]">
            <div class="flex justify-between items-start mb-2">
                <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-400 text-[8px] font-black uppercase rounded border border-emerald-500/30 tracking-widest text-[7px]">Argent au Coffre</span>
                <i class="fas fa-piggy-bank text-emerald-500 text-[10px]"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-xl font-black">{{ number_format($realTreasury, 0, ',', ' ') }}</h3>
                <small class="text-[9px] font-black opacity-30 uppercase">FCFA</small>
            </div>
            <p class="text-[8px] font-bold text-gray-500 mt-2 uppercase tracking-tighter">Mobile + Versements Confirmés</p>
        </div>

        <!-- CASHIER HOLDINGS (NON-VERIFIED) -->
        <div class="bg-indigo-600 px-5 py-4 rounded-2xl shadow-lg text-white xl:min-w-[240px]">
            <div class="flex justify-between items-start mb-2">
                <span class="px-2 py-0.5 bg-white/10 text-white text-[8px] font-black uppercase rounded border border-white/20 tracking-widest text-[7px]">Reste en Caisses</span>
                <i class="fas fa-hand-holding-usd text-white/50 text-[10px]"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-xl font-black">{{ number_format($cashierHoldings, 0, ',', ' ') }}</h3>
                <small class="text-[9px] font-black opacity-50 uppercase">FCFA</small>
            </div>
            <p class="text-[8px] font-bold text-indigo-100 mt-2 uppercase tracking-tighter">Espèces non encore versées</p>
        </div>

        <!-- INSURANCE RECEIVABLES -->
        <div class="bg-white px-5 py-4 rounded-2xl shadow-sm border border-purple-100 xl:min-w-[240px]">
            <div class="flex justify-between items-start mb-2">
                <span class="px-2 py-0.5 bg-purple-50 text-purple-600 text-[8px] font-black uppercase rounded border border-purple-100 tracking-widest text-[7px]">Créances Assurances</span>
                <i class="fas fa-shield-alt text-purple-400 text-[10px]"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-xl font-black text-gray-900">{{ number_format($totalInsurance, 0, ',', ' ') }}</h3>
                <small class="text-[9px] font-black text-gray-300 uppercase">FCFA</small>
            </div>
            <p class="text-[8px] font-bold text-gray-400 mt-2 uppercase tracking-tighter">Part assurance à recouvrer</p>
        </div>
    </div>
</div>

<div x-data="{ tab: 'mobile' }" class="animate-fade-in">
    <!-- Local Tabs -->
    <div class="flex space-x-1 bg-gray-100 p-1 rounded-xl w-fit mb-6">
        <button 
            @click="tab = 'mobile'" 
            :class="tab === 'mobile' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-wide transition-all flex items-center gap-2"
        >
            <i class="fas fa-mobile-alt" :class="tab === 'mobile' ? 'text-orange-500' : ''"></i>
            Fonds API (Mobile)
        </button>
        <button 
            @click="tab = 'cash'" 
            :class="tab === 'cash' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-wide transition-all flex items-center gap-2"
        >
            <i class="fas fa-money-bill-wave" :class="tab === 'cash' ? 'text-indigo-500' : ''"></i>
            Fonds Cash (Physique)
        </button>
        <button 
            @click="tab = 'insurance'" 
            :class="tab === 'insurance' ? 'bg-white shadow text-gray-900' : 'text-gray-500 hover:text-gray-700'"
            class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-wide transition-all flex items-center gap-2"
        >
            <i class="fas fa-shield-alt" :class="tab === 'insurance' ? 'text-purple-500' : ''"></i>
            Assurance (Créances)
        </button>
    </div>

    <!-- Mobile Tab Content -->
    <div x-show="tab === 'mobile'" x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-95" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 bg-orange-50/10">
                <h3 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                    Transactions Mobile Money (API)
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Date / Heure</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Référence</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Patient</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Montant Total</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($mobileInvoices as $invoice)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-gray-900">{{ $invoice->created_at->format('d/m/Y') }}</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase font-mono">{{ $invoice->created_at->format('H:i:s') }}</p>
                                </td>
                                <td class="px-6 py-4 text-xs font-black text-blue-600">{{ $invoice->invoice_number }}</td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-gray-900">{{ $invoice->patient->name ?? 'Inconnu' }}</p>
                                    <p class="text-[9px] font-bold text-gray-400">{{ $invoice->service->name ?? 'Général' }}</p>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-gray-900 text-sm">
                                    {{ number_format($invoice->total, 0, ',', ' ') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[9px] font-black rounded-full uppercase border border-emerald-200">
                                        <i class="fas fa-check-circle mr-1"></i> Crédité
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $mobileInvoices->appends(['tab' => 'mobile'])->links() }}
            </div>
        </div>
    </div>

    <!-- Cash Tab Content -->
    <div x-show="tab === 'cash'" x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-95" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="display: none;">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 bg-indigo-50/10">
                <h3 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    Versements Physiques Confirmés
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Date Validation</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Caissière</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Notes</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Montant</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($cashTransfers as $transfer)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-gray-900">{{ ($transfer->validated_at ?? $transfer->created_at)->format('d M Y') }}</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase font-mono">{{ ($transfer->validated_at ?? $transfer->created_at)->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center text-[9px] font-black text-indigo-700">{{ substr($transfer->cashier->name ?? '?', 0, 1) }}</span>
                                        <span class="text-xs font-black text-gray-800">{{ $transfer->cashier->name ?? 'Inconnue' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[10px] font-bold text-gray-500 italic">{{ $transfer->notes ?? 'Clôture journalière' }}</td>
                                <td class="px-6 py-4 text-right font-black text-gray-900 text-sm">
                                    {{ number_format($transfer->amount, 0, ',', ' ') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($transfer->status === 'confirmed')
                                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[9px] font-black rounded-full uppercase border border-emerald-200">
                                            <i class="fas fa-check-circle mr-1"></i> Confirmé
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[9px] font-black rounded-full uppercase border border-amber-200 animate-pulse">
                                            <i class="fas fa-clock mr-1"></i> En Attente
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
             <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $cashTransfers->appends(['tab' => 'cash'])->links() }}
            </div>
        </div>
    </div>

    <!-- Insurance Tab Content -->
    <div x-show="tab === 'insurance'" x-transition:enter="transition ease-out duration-300 transform opacity-0 scale-95" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" style="display: none;">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 bg-purple-50/20">
                <h3 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                    Créances Assurances (Prise en Charge)
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Assureur</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Patient / Facture</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Part Assurance</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($insuranceInvoices as $invoice)
                            @php
                                $insurancePart = ($invoice->total * ($invoice->insurance_coverage_rate ?? 0)) / 100;
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-xs font-bold text-gray-900 font-mono">{{ $invoice->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-purple-50 text-purple-700 text-[10px] font-black rounded border border-purple-100 uppercase">
                                        {{ $invoice->insurance_name ?? 'Inconnu' }}
                                    </span>
                                    <p class="text-[9px] font-bold text-gray-400 mt-1 uppercase">Couverture: {{ $invoice->insurance_coverage_rate ?? 0 }}%</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-gray-900">{{ $invoice->patient->name ?? 'Inconnu' }}</p>
                                    <p class="text-[9px] font-bold text-blue-600 uppercase">{{ $invoice->invoice_number }}</p>
                                </td>
                                <td class="px-6 py-4 text-right font-black text-purple-600 text-sm">
                                    {{ number_format($insurancePart, 0, ',', ' ') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusColor = match($invoice->insurance_settlement_status) {
                                            'recovered' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            default => 'bg-gray-100 text-gray-500 border-gray-200'
                                        };
                                        $statusLabel = match($invoice->insurance_settlement_status) {
                                            'recovered' => 'RECOUVRÉ',
                                            'pending' => 'À RECOUVRER',
                                            default => 'À TRAITER'
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[8px] font-black border {{ $statusColor }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
             <div class="p-4 border-t border-gray-100 bg-gray-50">
                {{ $insuranceInvoices->appends(['tab' => 'insurance'])->links() }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection
