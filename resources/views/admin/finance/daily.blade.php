@extends('layouts.app')

@section('title', 'Détails Recettes du Jour')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.finance.index') }}" class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm">
                <i class="fas fa-chevron-left"></i>
            </a>
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Recettes du Jour</h1>
                <p class="text-sm font-bold text-gray-500 uppercase tracking-widest">{{ now()->format('d F Y') }}</p>
            </div>
        </div>
        <div class="bg-gray-900 px-6 py-4 rounded-2xl shadow-xl text-white">
            <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Total Encaissé (Auj.)</p>
            <h2 class="text-2xl font-black">{{ number_format($statsByMethod->sum('total'), 0, ',', ' ') }} <small class="text-xs opacity-70">FCFA</small></h2>
        </div>
    </div>

    <!-- HORIZONTAL TABS (Sidebar Horizontal) -->
    <div class="flex flex-wrap gap-2 mb-8 bg-gray-100 p-1.5 rounded-2xl w-fit">
        <a href="{{ route('admin.finance.daily') }}" 
           class="px-8 py-3 rounded-xl text-sm font-black transition-all flex items-center {{ !$method ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
            Toutes les transactions
        </a>
        @foreach($statsByMethod as $key => $stat)
            <a href="{{ route('admin.finance.daily', ['method' => $key]) }}" 
               class="px-8 py-3 rounded-xl text-sm font-black transition-all flex items-center {{ $method === $key ? 'bg-white shadow-sm text-gray-900' : 'text-gray-500 hover:text-gray-700' }}">
                <i class="fas {{ $key === 'cash' ? 'fa-money-bill-wave text-emerald-500' : 'fa-mobile-alt text-orange-500' }} mr-2"></i>
                {{ $stat['label'] }}
                <span class="ml-2 bg-gray-200 text-gray-600 px-2 py-0.5 rounded-lg text-[10px]">{{ number_format($stat['total'], 0, ',', ' ') }}</span>
            </a>
        @endforeach
    </div>

    <!-- CAISSE BREAKDOWN SECTION -->
    <div class="mb-8">
        <h3 class="font-black text-gray-900 uppercase tracking-widest text-xs mb-4 flex items-center">
            <i class="fas fa-cash-register mr-2 text-blue-500"></i> Répartition par Point d'Encaissement (Aujourd'hui)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach(['accueil' => 'Accueil & Consultation', 'labo' => 'Laboratoire', 'urgence' => 'Urgences'] as $id => $label)
                <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 bg-gray-100 rounded-lg text-[10px] font-black uppercase text-gray-600">{{ $label }}</span>
                        <span class="text-sm font-black text-gray-900">{{ number_format($statsByCaisse[$id]['total'], 0, ',', ' ') }} <small class="text-[10px] text-gray-400">FCFA</small></span>
                    </div>
                    
                    <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Caissière(s) en poste :</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse($statsByCaisse[$id]['cashiers'] as $cid => $cname)
                            <div class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-blue-50 border border-blue-100">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2 animate-pulse"></div>
                                <span class="text-[10px] font-bold text-blue-800 uppercase tracking-tight">{{ $cname }}</span>
                            </div>
                        @empty
                            <span class="text-[10px] font-bold text-gray-300 italic">Aucune activité aujourd'hui</span>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- MAIN TABLE -->
    <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <h3 class="font-black text-gray-900 uppercase tracking-widest text-xs">
                {{ $method ? 'Filtré par: ' . strtoupper($method) : 'Journal complet des paiements' }}
            </h3>
            <span class="px-4 py-1.5 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase">{{ $invoices->count() }} opérations</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Heure</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Référence</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Caisse</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Patient</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Montant</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Méthode</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Caissière</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($invoices as $invoice)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-5 text-sm font-bold text-gray-900">{{ $invoice->created_at->format('H:i') }}</td>
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
                                <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $invoice->service->name ?? 'Général' }}</p>
                            </td>
                            <td class="px-8 py-5 text-right text-lg font-black text-gray-900">{{ number_format($invoice->total, 0, ',', ' ') }}</td>
                            <td class="px-8 py-5 text-center">
                                @php
                                    $methodColor = match(strtolower($invoice->payment_method)) {
                                        'cash', 'espèces', 'especes' => 'bg-emerald-100 text-emerald-700',
                                        'mobile_money', 'mobile money', 'momo' => 'bg-orange-100 text-orange-700',
                                        default => 'bg-gray-100 text-gray-600'
                                    };
                                @endphp
                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase {{ $methodColor }}">
                                    {{ $invoice->payment_method }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @if($invoice->cashier)
                                    <div class="flex items-center justify-center">
                                        <span class="text-xs font-bold text-gray-600">{{ $invoice->cashier->name }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
