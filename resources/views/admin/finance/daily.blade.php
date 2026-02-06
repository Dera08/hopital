@extends('layouts.admin_finance')

@section('title', 'Détails Recettes du Jour')

@section('finance_content')
<div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-6 animate-fade-in">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            <i class="fas fa-calendar-day text-blue-500"></i> Recettes du Jour
        </h2>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">
             Détail des transactions du {{ now()->translatedFormat('d F Y') }}
        </p>
    </div>
    
@php
    $totalMobile = ($statsByMethod['mobile']['total'] ?? 0) + ($statsByMethod['card']['total'] ?? 0);
    $realTreasury = $totalMobile + $confirmedTransfersTotal;
    $cashAtHand = ($statsByMethod['cash']['total'] ?? 0) - $confirmedTransfersTotal;
    if($cashAtHand < 0) $cashAtHand = 0; // Guard against overlapping dates if any
@endphp

<div class="flex flex-col xl:flex-row justify-between items-start xl:items-center mb-8 gap-6 animate-fade-in">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            <i class="fas fa-university text-blue-500"></i> Trésorerie du Jour
        </h2>
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">
             Position financière au {{ now()->translatedFormat('d F Y à H:i') }}
        </p>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full xl:w-auto">
        <!-- TREASURY LIQUIDITY (MOBILE + CONFIRMED) -->
        <div class="bg-gray-900 px-5 py-4 rounded-2xl shadow-xl text-white xl:min-w-[240px]">
            <div class="flex justify-between items-start mb-2">
                <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-400 text-[8px] font-black uppercase rounded border border-emerald-500/30 tracking-widest text-[7px]">Réalisé Trésorerie</span>
                <i class="fas fa-vault text-emerald-500 text-[10px]"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-xl font-black">{{ number_format($realTreasury, 0, ',', ' ') }}</h3>
                <small class="text-[9px] font-black opacity-30 uppercase">FCFA</small>
            </div>
            <p class="text-[8px] font-bold text-gray-500 mt-2 uppercase tracking-tighter">Mobile + Versements Confirmés</p>
        </div>

        <!-- CASH AT HAND (CASHIER BOXES) -->
        <div class="bg-blue-600 px-5 py-4 rounded-2xl shadow-lg text-white xl:min-w-[240px]">
            <div class="flex justify-between items-start mb-2">
                <span class="px-2 py-0.5 bg-white/10 text-white text-[8px] font-black uppercase rounded border border-white/20 tracking-widest text-[7px]">Encours Caisses</span>
                <i class="fas fa-cash-register text-white/50 text-[10px]"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-xl font-black">{{ number_format($cashAtHand, 0, ',', ' ') }}</h3>
                <small class="text-[9px] font-black opacity-50 uppercase">FCFA</small>
            </div>
            <p class="text-[8px] font-bold text-blue-100 mt-2 uppercase tracking-tighter">Espèces en main (Non versé)</p>
        </div>

        <!-- PENDING RECEIVABLES (INSURANCE + UNPAID) -->
        <div class="bg-white px-5 py-4 rounded-2xl shadow-sm border border-orange-100 xl:min-w-[240px]">
            <div class="flex justify-between items-start mb-2">
                <span class="px-2 py-0.5 bg-orange-50 text-orange-600 text-[8px] font-black uppercase rounded border border-orange-100 tracking-widest text-[7px]">À Collecter (Dû)</span>
                <i class="fas fa-hand-holding-usd text-orange-400 text-[10px]"></i>
            </div>
            <div class="flex items-baseline gap-2">
                <h3 class="text-xl font-black text-gray-900">{{ number_format($totals->pending, 0, ',', ' ') }}</h3>
                <small class="text-[9px] font-black text-gray-300 uppercase">FCFA</small>
            </div>
            <p class="text-[8px] font-bold text-gray-400 mt-2 uppercase tracking-tighter">Assurances + Impayés Patients</p>
        </div>
    </div>
</div>

<!-- Local Filters (Payment Method) -->
<div class="flex flex-wrap gap-2 mb-8 animate-fade-in">
    <a href="{{ route('admin.finance.daily') }}" 
       class="px-5 py-2 rounded-lg text-xs font-black uppercase tracking-wide transition-all border {{ !$method ? 'bg-blue-600 border-blue-600 text-white shadow-md' : 'bg-white border-gray-200 text-gray-500 hover:border-gray-300' }}">
        Tout
    </a>
    @foreach($statsByMethod as $key => $stat)
        <a href="{{ route('admin.finance.daily', ['method' => $key]) }}" 
           class="px-5 py-2 rounded-lg text-xs font-black uppercase tracking-wide transition-all border flex items-center gap-2 
           {{ $method === $key ? ($key === 'insurance' ? 'bg-purple-600 border-purple-600 text-white shadow-md' : 'bg-blue-600 border-blue-600 text-white shadow-md') : 'bg-white border-gray-200 text-gray-500 hover:border-gray-300' }}">
            <i class="fas {{ $key === 'cash' ? 'fa-money-bill-wave' : ($key === 'mobile' ? 'fa-mobile-alt' : ($key === 'card' ? 'fa-credit-card' : 'fa-shield-alt')) }}"></i>
            {{ $stat['label'] }}
        </a>
    @endforeach
</div>

<!-- CAISSE BREAKDOWN SECTION -->
<div class="mb-8 animate-fade-in">
    <h3 class="font-black text-gray-800 uppercase tracking-widest text-xs mb-4 flex items-center">
        <i class="fas fa-cash-register mr-2 text-gray-400"></i> Performance par Caisse
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach(['accueil' => 'Accueil & Consultation', 'labo' => 'Laboratoire', 'urgence' => 'Urgences'] as $id => $label)
             @php 
                $color = match($id) { 'accueil' => 'indigo', 'labo' => 'teal', 'urgence' => 'rose', default => 'gray' }; 
                $isActive = $caisse === $id;
             @endphp
            <div class="bg-white p-5 rounded-2xl border {{ $isActive ? 'border-'.$color.'-500 ring-2 ring-'.$color.'-100' : 'border-gray-100' }} shadow-sm relative overflow-hidden group hover:border-{{ $color }}-200 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <span class="px-2 py-1 bg-{{ $color }}-50 text-{{ $color }}-600 rounded-md text-[9px] font-black uppercase tracking-wider">{{ $label }}</span>
                    <div class="text-right">
                        <span class="text-lg font-black text-gray-900 block">{{ number_format($statsByCaisse[$id]['total'], 0, ',', ' ') }} <small class="text-[9px] text-gray-400 font-black">FCFA</small></span>
                        @if($method)
                            <span class="text-[8px] font-black uppercase text-{{ $color }}-500">
                                @if($method === 'cash') (En Espèces)
                                @elseif($method === 'mobile') (Mobile Money)
                                @elseif($method === 'insurance') (Part Assurance)
                                @endif
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="flex justify-between items-end">
                    <div class="flex flex-wrap gap-1">
                        @forelse($statsByCaisse[$id]['cashiers'] as $cid => $cname)
                            <div class="inline-flex items-center px-2 py-1 rounded bg-gray-50 text-[9px] font-bold text-gray-500">
                                <div class="w-1.5 h-1.5 rounded-full bg-{{ $color }}-400 mr-1.5"></div>
                                {{ $cname }}
                            </div>
                        @empty
                            <span class="text-[9px] font-bold text-gray-300 italic">Inactif</span>
                        @endforelse
                    </div>

                    <a href="{{ route('admin.finance.daily', array_merge(request()->query(), ['caisse' => $id])) }}" 
                       class="text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg {{ $isActive ? 'bg-'.$color.'-600 text-white' : 'bg-'.$color.'-50 text-'.$color.'-600 hover:bg-'.$color.'-600 hover:text-white' }} transition-all flex items-center gap-1">
                        @if($isActive) <i class="fas fa-check"></i> @endif
                        Voir Détails
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- MAIN TABLE -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in" id="transaction-journal">
    <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <div class="flex items-center gap-3">
            <h3 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
                <i class="fas fa-list text-gray-400"></i> Journal des Transactions
            </h3>
            @if($caisse)
                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[8px] font-black uppercase rounded-md border border-blue-200">
                    Filtré par: {{ ucfirst($caisse) }}
                </span>
                <a href="{{ route('admin.finance.daily', request()->except('caisse')) }}" class="text-[8px] font-black text-red-500 uppercase hover:underline">Effacer</a>
            @endif
        </div>
        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase">{{ $invoices->count() }} lignes</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Heure</th>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Référence</th>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Services</th>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Montant</th>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Via / Méthode</th>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Statut de Paiement</th>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Caissière</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($invoices as $invoice)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-xs font-bold text-gray-900 font-mono">{{ $invoice->created_at->format('H:i') }}</td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-blue-600 block">{{ $invoice->invoice_number }}</span>
                             <span class="text-[10px] text-gray-400 font-bold block">{{ $invoice->patient->name ?? 'Inconnu' }}</span>
                        </td>
                        <td class="px-6 py-4">
                             @php
                                $wc = 'gray';
                                if ($invoice->service) {
                                    if ($invoice->service->caisse_type === 'labo' || strpos(strtolower($invoice->service->name), 'labo') !== false) $wc = 'teal';
                                    elseif ($invoice->service->caisse_type === 'urgence' || strpos(strtolower($invoice->service->name), 'urgence') !== false) $wc = 'rose';
                                }
                            @endphp
                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase bg-{{ $wc }}-50 text-{{ $wc }}-600">
                                {{ $invoice->service->name ?? 'Général' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                             @php
                                $insurancePart = ($invoice->total * ($invoice->insurance_coverage_rate ?? 0)) / 100;
                                $patientPart = $invoice->total - $insurancePart;
                                
                                // Display logic: if filter is 'insurance', show insurance part. Else show total or patient part?
                                // User said: "tu donne exactement le montant des 80 pourcent"
                                $displayAmount = $invoice->total;
                                if ($method === 'insurance') {
                                    $displayAmount = $insurancePart;
                                } elseif ($method === 'cash' || $method === 'mobile') {
                                    $displayAmount = $patientPart;
                                }
                            @endphp
                            <span class="text-sm font-black text-gray-900 block">{{ number_format($displayAmount, 0, ',', ' ') }}</span>
                            @if(!$method && $insurancePart > 0)
                                <span class="text-[9px] font-bold text-purple-500 whitespace-nowrap">Incl. {{ number_format($insurancePart, 0, ',', ' ') }} Assur.</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $methodLower = strtolower($invoice->payment_method);
                                $isMobile = in_array($methodLower, ['mobile_money', 'mobile money', 'momo']);
                                $hasInsurance = $invoice->insurance_coverage_rate > 0;
                            @endphp
                            <div class="flex flex-col items-center gap-1">
                                <!-- Patient Method -->
                                <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase {{ in_array($methodLower, ['cash', 'espèces', 'especes']) ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : ($isMobile ? 'bg-orange-50 text-orange-600 border border-orange-100' : 'bg-gray-50 text-gray-600') }}">
                                    {{ $invoice->payment_method }}
                                </span>
                                
                                <!-- Insurance Method (if applicable) -->
                                @if($hasInsurance)
                                    <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase bg-purple-50 text-purple-600 border border-purple-100">
                                        Assurance: {{ $invoice->insurance_name }} ({{ $invoice->insurance_coverage_rate }}%)
                                    </span>
                                @endif
                                
                                @if($isMobile && $invoice->payment_operator)
                                    <span class="text-[8px] font-bold text-gray-400 uppercase">{{ $invoice->payment_operator }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $isFullyPaid = $invoice->status === 'paid';
                                $hasInsurance = ($invoice->insurance_coverage_rate ?? 0) > 0;
                            @endphp
                            
                            <div class="flex flex-col items-center gap-1.5">
                                <!-- Global Payment Status -->
                                @if($isFullyPaid)
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[9px] font-black rounded-full border border-emerald-100 flex items-center gap-1.5">
                                        <i class="fas fa-check-circle"></i> PAYÉ
                                    </span>
                                @else
                                    <span class="px-3 py-1 bg-rose-50 text-rose-700 text-[9px] font-black rounded-full border border-rose-100 flex items-center gap-1.5 animate-pulse">
                                        <i class="fas fa-exclamation-circle"></i> EN ATTENTE
                                    </span>
                                @endif

                                <!-- Insurance Settlement status (secondary) -->
                                @if($hasInsurance)
                                    <div class="flex items-center gap-1 px-1.5 py-0.5 rounded-md bg-gray-50 border border-gray-100">
                                        <span class="text-[7px] font-black text-gray-400 uppercase">Assur:</span>
                                        <span class="text-[8px] font-black {{ $invoice->insurance_settlement_status === 'recovered' ? 'text-emerald-500' : 'text-amber-500' }} uppercase">
                                            {{ $invoice->insurance_settlement_status === 'recovered' ? 'Soldé' : 'Dû' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-bold text-gray-500">
                            {{ $invoice->cashier->name ?? '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
