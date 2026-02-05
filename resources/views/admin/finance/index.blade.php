@extends('layouts.app')

@section('title', 'Tableau de Bord Financier')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tight">Finance & Trésorerie</h1>
            <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-widest">Supervision Globale et Flux par Caisse</p>
        </div>
        <div class="mt-4 md:mt-0 flex gap-3">
             <span class="px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-xs font-black uppercase tracking-widest flex items-center">
                {{ now()->format('d F Y') }}
            </span>
        </div>
    </div>

    <!-- 1. GLOBAL KPIS (RESTORED) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Recettes du Jour -->
        <a href="{{ route('admin.finance.daily') }}" class="bg-gray-900 p-6 rounded-2xl shadow-xl relative overflow-hidden group hover:ring-2 hover:ring-blue-500 transition-all cursor-pointer">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fas fa-coins text-6xl text-white"></i>
            </div>
            <div class="flex justify-between items-start mb-1">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Recettes du Jour</p>
                <i class="fas fa-arrow-right text-[10px] text-gray-600 group-hover:text-blue-400"></i>
            </div>
            <h3 class="text-3xl font-black text-white">{{ number_format($revenueToday, 0, ',', ' ') }} <span class="text-sm text-gray-500 font-bold">FCFA</span></h3>
            <div class="mt-4 flex items-center text-xs font-bold {{ $growth >= 0 ? 'text-emerald-400' : 'text-red-400' }}">
                <i class="fas fa-chart-line mr-1"></i> {{ $growth > 0 ? '+' : '' }}{{ number_format($growth, 1) }}% vs Hier
            </div>
        </a>

        <!-- API / Mobile Money (Daily Focus) -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 rounded-2xl shadow-lg relative overflow-hidden group text-white">
            <div class="absolute top-0 right-0 p-4 opacity-20">
                <i class="fas fa-mobile-alt text-6xl"></i>
            </div>
            <p class="text-xs font-black text-orange-100 uppercase tracking-widest mb-1">Flux API Mobile (Auj.)</p>
            <h3 class="text-3xl font-black">{{ number_format($totalMobileToday, 0, ',', ' ') }} <span class="text-sm text-orange-200 font-bold">FCFA</span></h3>
            <div class="mt-4 flex items-center text-xs font-bold text-orange-100">
                <i class="fas fa-wifi mr-1 animate-pulse"></i> Réconciliation: {{ $momoReconciliationStatus === 'balanced' ? 'Équilibré' : 'Vérification' }}
            </div>
        </div>

        <!-- Recettes du Mois -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fas fa-calendar-alt text-6xl text-blue-500"></i>
            </div>
            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Recettes du Mois</p>
            <h3 class="text-3xl font-black text-gray-900">{{ number_format($revenueMonth, 0, ',', ' ') }} <span class="text-sm text-gray-400 font-bold">FCFA</span></h3>
        </div>

        <!-- Trésorerie / Unpaid -->
        <div class="bg-red-50 p-6 rounded-2xl shadow-sm border border-red-100 relative overflow-hidden group hover:shadow-lg transition-all">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fas fa-file-invoice-dollar text-6xl text-red-500"></i>
            </div>
            <p class="text-xs font-black text-red-400 uppercase tracking-widest mb-1">Reste à Recouvrer</p>
            <h3 class="text-3xl font-black text-red-600">{{ number_format($pendingRevenue, 0, ',', ' ') }} <span class="text-sm text-red-400 font-bold">FCFA</span></h3>
        </div>
    </div>

    <!-- 2. FLUX PAR CAISSE (RESTORED) -->
    <div class="mb-12">
        <h3 class="font-black text-gray-900 uppercase tracking-widest text-sm mb-4 flex items-center">
            <i class="fas fa-cash-register mr-2 text-gray-400"></i> Flux par point d'encaissement (Aujourd'hui)
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach(['accueil' => 'Accueil & Consultation', 'labo' => 'Laboratoire & Analyses', 'urgence' => 'Urgences'] as $key => $label)
                @php $stats = $caisseStats[$key]; @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full hover:shadow-md transition-shadow">
                    <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <span class="text-xs font-black uppercase tracking-widest text-gray-600">{{ $label }}</span>
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs">
                            {{ $stats['count'] }}
                        </div>
                    </div>
                    <div class="p-6 flex-1">
                        <div class="flex justify-between items-baseline mb-4">
                            <h4 class="text-2xl font-black text-gray-900">{{ number_format($stats['total'], 0, ',', ' ') }} <small class="text-xs text-gray-400">FCFA</small></h4>
                        </div>
                        
                        <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden flex mb-4">
                            @if($stats['total'] > 0)
                                <div class="h-full bg-emerald-500" style="width: {{ ($stats['cash'] / $stats['total']) * 100 }}%"></div>
                                <div class="h-full bg-orange-500" style="width: {{ ($stats['mobile'] / $stats['total']) * 100 }}%"></div>
                            @endif
                        </div>
                        
                        <div class="flex justify-between text-xs font-bold text-gray-500 mb-6">
                            <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-1"></span> Espèces: {{ number_format($stats['cash'], 0, ',', ' ') }}</span>
                            <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-orange-500 mr-1"></span> Mobile: {{ number_format($stats['mobile'], 0, ',', ' ') }}</span>
                        </div>

                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase mb-2">Caissiers Actifs</p>
                            <div class="flex flex-wrap gap-2">
                                @forelse($stats['active_cashiers'] as $cashier)
                                    <div class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 border border-gray-200" title="{{ $cashier->name }}">
                                        <div class="w-1.5 h-1.5 rounded-full bg-indigo-500 mr-2 animate-pulse"></div>
                                        <span class="text-[10px] font-bold text-gray-700 uppercase tracking-tight">{{ $cashier->name }}</span>
                                    </div>
                                @empty
                                    <span class="text-xs text-gray-400 italic font-medium">Aucun actif aujourd'hui</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 3. VERSEMENTS EN ATTENTE (WITH GAP CONTROL) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
        <div class="lg:col-span-2 bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-black text-gray-900 uppercase tracking-widest text-sm flex items-center">
                    <i class="fas fa-check-double text-emerald-500 mr-2"></i> Section 1: Versements en Attente (Validation)
                </h3>
                <span class="bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded-lg {{ $pendingTransfers->count() > 0 ? 'animate-pulse' : '' }}">
                    {{ $pendingTransfers->count() }} Attente(s)
                </span>
            </div>
            <div class="p-0">
                @if($pendingTransfers->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Caissière</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider text-right">Montant Déclaré</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider text-center">Validation Physique</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($pendingTransfers as $transfer)
                                    <tr class="hover:bg-gray-50/50 transition-colors">
                                        <td class="px-6 py-4 text-xs font-bold text-gray-500">{{ $transfer->created_at->format('d/m H:i') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-8 w-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-black text-xs mr-3">
                                                    {{ substr($transfer->cashier->name ?? '?', 0, 2) }}
                                                </div>
                                                <span class="text-sm font-black text-gray-900">{{ $transfer->cashier->name ?? 'Caissière' }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right text-lg font-black text-gray-900">
                                            {{ number_format($transfer->amount, 0, ',', ' ') }}
                                        </td>
                                        <td class="px-6 py-4 text-center" x-data="{ validating: false, received: {{ $transfer->amount }} }">
                                            <button @click="validating = true" x-show="!validating" class="bg-emerald-600 text-white text-[10px] font-black uppercase px-4 py-2 rounded-xl shadow-lg shadow-emerald-100 hover:scale-105 transition-all">
                                                Comptage ok
                                            </button>
                                            
                                            <div x-show="validating" class="flex flex-col items-center gap-2">
                                                <div class="flex gap-1">
                                                    <input type="number" x-model="received" class="w-24 px-2 py-1 bg-gray-50 border border-gray-200 rounded text-xs font-black">
                                                    <form action="{{ route('admin.finance.confirm', $transfer->id) }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="received_amount" :value="received">
                                                        <button type="submit" class="bg-indigo-600 text-white p-1.5 rounded text-xs"><i class="fas fa-check"></i></button>
                                                    </form>
                                                    <button @click="validating = false" class="bg-gray-100 text-gray-400 p-1.5 rounded text-xs"><i class="fas fa-times"></i></button>
                                                </div>
                                                <p class="text-[9px] font-bold text-orange-500" x-show="received != {{ $transfer->amount }}">Écart de Caisse: <span x-text="Math.abs(received - {{ $transfer->amount }}).toLocaleString()"></span> F</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-12 text-center text-gray-400 italic font-bold text-sm">Aucun versement en attente.</div>
                @endif
            </div>
        </div>

        <!-- 4. MODES DE PAIEMENT (DONUT CHART) -->
        <div class="bg-white rounded-3xl shadow-lg border border-gray-100 p-6 flex flex-col">
            <h3 class="font-black text-gray-900 uppercase tracking-widest text-sm mb-6 flex items-center">
                <i class="fas fa-chart-pie text-gray-400 mr-2"></i> Section 3: Modes de Paiement
            </h3>
            <div class="flex-1 min-h-[220px] relative">
                <canvas id="paymentMethodsChart"></canvas>
            </div>
            <div class="mt-4 flex justify-between text-[10px] font-black uppercase text-gray-400">
                <span class="flex items-center"><span class="w-2 h-2 rounded bg-emerald-500 mr-1"></span> Cash</span>
                <span class="flex items-center"><span class="w-2 h-2 rounded bg-orange-500 mr-1"></span> Mobile API</span>
            </div>
        </div>
    </div>

    <!-- 5. MONITORING MOBILE MONEY & SERVICE PERFORMANCE -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Monitoring MoMo -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-50 bg-indigo-50/30">
                <h3 class="font-black text-gray-900 uppercase tracking-widest text-sm flex items-center">
                    <i class="fas fa-satellite-dish text-indigo-500 mr-2"></i> Section 2: Monitoring Mobile Money (API)
                </h3>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Patient / Facture</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider">Opérateur</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider text-right">Montant</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-wider text-center">Réconcilié</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($mobileInvoicesToday as $inv)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <p class="text-sm font-black text-gray-900">{{ $inv->patient->name ?? 'Patient' }}</p>
                                        <p class="text-[10px] font-bold text-indigo-600 uppercase">#{{ $inv->invoice_number }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-bold text-gray-600">{{ $inv->payment_method }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-gray-900">{{ number_format($inv->total, 0, ',', ' ') }} F</td>
                                    <td class="px-6 py-4 text-center">
                                        <i class="fas fa-check-circle text-emerald-500"></i>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="p-8 text-center text-gray-400 italic">Aucune transaction MoMo aujourd'hui</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Performance par Service -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
            <h3 class="font-black text-gray-900 uppercase tracking-widest text-sm mb-8 flex items-center">
                <i class="fas fa-chart-bar text-gray-400 mr-2"></i> Rentabilité par Service
            </h3>
            <div class="space-y-6">
                @foreach($revenueByService as $service)
                    <div class="group">
                        <div class="flex justify-between items-baseline mb-2">
                            <span class="text-sm font-black text-gray-700 tracking-tight">{{ $service->name }}</span>
                            <span class="text-sm font-black text-gray-900">{{ number_format($service->total, 0, ',', ' ') }} <small class="text-[10px] text-gray-400">F</small></span>
                        </div>
                        <div class="w-full h-2 bg-gray-50 rounded-full overflow-hidden">
                            @php $pct = $revenueToday > 0 ? ($service->total / $revenueToday) * 100 : 0; @endphp
                            <div class="h-full bg-indigo-500 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- 6. HISTORIQUE & AUDIT (RESTORED TRANSAC LOG) -->
    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden mb-8">
        <div class="p-8 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center bg-gray-50/30 gap-4">
            <h3 class="font-black text-gray-900 uppercase tracking-widest text-xs flex items-center">
                <i class="fas fa-history text-gray-400 mr-2"></i> Section 4: Historique des Transactions & Audit
            </h3>
            <div class="flex gap-2">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" placeholder="Rechercher..." class="pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-600 focus:ring-2 focus:ring-indigo-500 w-full md:w-64">
                </div>
                <a href="{{ route('admin.finance.export') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-50 flex items-center">
                    <i class="fas fa-file-excel mr-1"></i> Export
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-wider">Heure</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-wider">Service</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-wider">Patient</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-wider text-right">Montant</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-wider text-center">Méthode</th>
                        <th class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-wider text-center">Caissière</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($latestInvoices as $invoice)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-4 whitespace-nowrap text-xs font-bold text-gray-500">{{ $invoice->created_at->format('H:i') }}</td>
                            <td class="px-8 py-4 whitespace-nowrap text-xs font-black text-gray-700 uppercase tracking-tighter">{{ $invoice->service->name ?? 'Général' }}</td>
                            <td class="px-8 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $invoice->patient->name ?? 'Patient' }}</td>
                            <td class="px-8 py-4 whitespace-nowrap text-right text-sm font-black text-gray-900">{{ number_format($invoice->total, 0, ',', ' ') }} F</td>
                            <td class="px-8 py-4 whitespace-nowrap text-center">
                                <span class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-tighter {{ str_contains(strtolower($invoice->payment_method), 'momo') || str_contains(strtolower($invoice->payment_method), 'mobile') ? 'bg-orange-100 text-orange-700' : 'bg-emerald-100 text-emerald-700' }}">
                                    {{ $invoice->payment_method }}
                                </span>
                            </td>
                            <td class="px-8 py-4 whitespace-nowrap text-center">
                                <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $invoice->cashier->name ?? 'Système' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-8 py-10 text-center text-gray-400 italic">Aucune transaction trouvée</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('paymentMethodsChart');
        if(!ctx) return;

        const dataArr = @json($revenueByMethod);
        
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Cash', 'Mobile API'],
                datasets: [{
                    data: [dataArr.cash || 0, dataArr.mobile || 0],
                    backgroundColor: ['#10B981', '#F97316'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '75%',
            }
        });
    });
</script>
@endpush

@endsection
