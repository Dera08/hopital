@extends('layouts.cashier_layout')

@section('content')
<div class="p-4 sm:p-8 bg-gray-50 min-h-screen">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h2 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tight">Historique des Paiements</h2>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">Consulter et auditer les transactions passées</p>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <button class="bg-green-600 text-white px-6 py-3 rounded-2xl shadow-xl shadow-green-100 hover:bg-green-700 flex items-center gap-3 font-black transition-all flex-1 md:flex-none justify-center">
                <i class="fas fa-file-excel"></i> Exporter
            </button>
        </div>
    </div>

    {{-- BARRE HORIZONTALE DE FILTRE (DATE) --}}
    <div class="flex flex-wrap gap-2 mb-8 bg-white p-2 rounded-2xl border border-gray-100 shadow-sm w-fit">
        @php
            $filters = [
                'today' => ['label' => "Aujourd'hui", 'icon' => 'fa-clock'],
                'yesterday' => ['label' => 'Hier', 'icon' => 'fa-history'],
                'this_week' => ['label' => 'Cette Semaine', 'icon' => 'fa-calendar-week'],
                'this_month' => ['label' => 'Ce Mois', 'icon' => 'fa-calendar-alt'],
                'all' => ['label' => 'Tout Voir', 'icon' => 'fa-list-ul'],
            ];
        @endphp

        @foreach($filters as $key => $filter)
            <a href="{{ route('cashier.payments.index', ['date_filter' => $key, 'search' => $search, 'method' => $method]) }}" 
               class="px-5 py-2.5 rounded-xl text-xs font-black transition-all flex items-center {{ ($dateFilter ?? 'today') === $key ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-gray-500 hover:bg-gray-50' }}">
                <i class="fas {{ $filter['icon'] }} mr-2"></i>
                {{ $filter['label'] }}
            </a>
        @endforeach
    </div>

    {{-- Filtres Avancés & Recherche --}}
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8">
        <form action="{{ route('cashier.payments.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="hidden" name="date_filter" value="{{ $dateFilter ?? 'today' }}">
            
            <div class="md:col-span-2 relative">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="N° Facture ou Nom Patient..." 
                       class="w-full pl-14 pr-6 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-sm text-gray-600">
            </div>

            <div class="relative">
                <select name="method" class="w-full pl-6 pr-10 py-3 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-sm text-gray-600 appearance-none">
                    <option value="all" {{ $method === 'all' ? 'selected' : '' }}>Toutes méthodes</option>
                    <option value="Espèces" {{ $method === 'Espèces' ? 'selected' : '' }}>Espèces (Cash)</option>
                    <option value="Mobile Money" {{ $method === 'Mobile Money' ? 'selected' : '' }}>Mobile Money (API)</option>
                </select>
                <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
            </div>

            <button type="submit" class="bg-indigo-600 text-white font-black py-3 rounded-2xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-50">
                <i class="fas fa-filter mr-2"></i> Appliquer filtres
            </button>
        </form>
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fas fa-wallet text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total encaissé</p>
                <p class="text-2xl font-black text-gray-900">{{ number_format($totalRevenue, 0, ',', ' ') }} <small class="text-xs text-gray-400 font-bold">FCFA</small></p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="fas fa-receipt text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Paiements</p>
                <p class="text-2xl font-black text-gray-900">{{ $paymentCount }} <small class="text-xs text-gray-400 font-bold">transactions</small></p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Panier Moyen</p>
                <p class="text-2xl font-black text-gray-900">{{ number_format($averagePayment, 0, ',', ' ') }} <small class="text-xs text-gray-400 font-bold">FCFA</small></p>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50 border-b border-gray-50">
                    <tr>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Référence</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Date & Heure</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Patient</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Montant</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Méthode</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="px-8 py-5">
                                <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-lg text-[10px] font-black tracking-tight border border-indigo-100">
                                    #{{ $payment->invoice_number }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-sm font-black text-gray-800">{{ $payment->invoice_date->format('d/m/Y') }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ $payment->created_at->format('H:i') }}</p>
                            </td>
                            <td class="px-8 py-5">
                                <p class="font-black text-gray-800 text-sm">{{ $payment->patient?->name ?? 'Patient Supprimé' }}</p>
                                <p class="text-[10px] font-bold text-gray-400 italic">IPU: {{ $payment->patient?->ipu ?? '-' }}</p>
                            </td>
                            <td class="px-8 py-5 text-right font-black text-gray-900 text-lg">
                                {{ number_format($payment->total, 0, ',', ' ') }} <small class="text-[10px] text-gray-400 italic">FCFA</small>
                            </td>
                            <td class="px-8 py-5 text-center">
                                @php
                                    $isMobile = str_contains(strtolower($payment->payment_method), 'mobile') || str_contains(strtolower($payment->payment_method), 'api');
                                @endphp
                                <span class="px-3 py-1.5 rounded-xl {{ $isMobile ? 'bg-orange-100 text-orange-700' : 'bg-emerald-100 text-emerald-700' }} text-[9px] font-black uppercase tracking-widest whitespace-nowrap">
                                    {{ $payment->payment_method ?? 'Espèces' }}
                                    @if($payment->payment_operator)
                                        ({{ $payment->payment_operator }})
                                    @endif
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right flex gap-2 justify-end">
                                <a href="{{ route('cashier.invoices.show', $payment->id) }}" class="p-2.5 bg-gray-100 text-gray-500 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm" title="Détails">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                <a href="{{ route('cashier.invoices.print', $payment->id) }}" target="_blank" class="p-2.5 bg-gray-100 text-gray-500 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Imprimer">
                                    <i class="fas fa-print text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-receipt text-gray-200 text-6xl mb-4"></i>
                                    <p class="text-gray-400 font-bold">Aucune transaction trouvée pour ces filtres</p>
                                    <p class="text-xs text-gray-300 mt-1">Ajustez vos critères de recherche ou de date</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payments->hasPages())
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection