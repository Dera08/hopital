@extends('layouts.admin_finance')

@section('title', 'Audit & Logs')

@section('finance_content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
    <div>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
            <i class="fas fa-history text-blue-500"></i> Audit & Historique
        </h2>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">
             Journal Complet des Transactions
        </p>
    </div>
    
    <!-- Search Form -->
    <form action="{{ route('admin.finance.audit') }}" method="GET" class="flex gap-2 w-full md:w-auto">
        <div class="relative flex-1 md:w-64">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher (Facture, Patient)..." 
                class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all shadow-sm">
        </div>
        <input type="date" name="date" value="{{ request('date') }}" class="px-3 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 focus:ring-2 focus:ring-blue-500 shadow-sm">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2.5 rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-100">
            <i class="fas fa-filter"></i>
        </button>
        @if(request()->has('search') || request()->has('date'))
            <a href="{{ route('admin.finance.audit') }}" class="bg-gray-100 text-gray-500 px-3 py-2.5 rounded-xl hover:bg-gray-200 transition-colors" title="Réinitialiser">
                <i class="fas fa-times"></i>
            </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in">
    <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
        <h3 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
            <i class="fas fa-list-alt text-gray-400"></i> Journal Global
        </h3>
        <a href="{{ route('admin.finance.export') }}" class="text-[10px] font-black uppercase text-blue-600 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors border border-blue-100">
            <i class="fas fa-download mr-1"></i> Exporter CSV
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Date / Heure</th>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Référence</th>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Patient</th>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Caisse</th> <!-- Added Caisse -->
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Service</th>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Montant</th>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Méthode</th>
                    <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Caissière</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-gray-900">{{ $log->created_at->format('d/m/Y') }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase font-mono">{{ $log->created_at->format('H:i:s') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-black text-blue-600">{{ $log->invoice_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-xs font-bold text-gray-900">{{ $log->patient->name ?? 'Inconnu' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $caisseName = 'N/A';
                                $caisseColor = 'bg-gray-50 text-gray-600';

                                if($log->cashier && $log->cashier->service) {
                                    // If cashier has a service, that's the "Caisse" they are at
                                    $caisseName = $log->cashier->service->name;
                                    
                                    // Style based on keywords
                                    if (stripos($caisseName, 'pharmacie') !== false) {
                                        $caisseColor = 'bg-emerald-50 text-emerald-700';
                                    } elseif (stripos($caisseName, 'labo') !== false) {
                                        $caisseColor = 'bg-blue-50 text-blue-700';
                                    } elseif (stripos($caisseName, 'urgence') !== false) {
                                        $caisseColor = 'bg-red-50 text-red-700';
                                    } else {
                                        $caisseColor = 'bg-indigo-50 text-indigo-700';
                                    }
                                } elseif ($log->service) {
                                    // Fallback to Invoice Service (Origin) if no cashier (e.g. system/unpaid)
                                    // But for Audit, we usually have a cashier.
                                    $srvName = strtolower($log->service->name);
                                    if ($log->service->caisse_type === 'labo' || strpos($srvName, 'labo') !== false) {
                                        $caisseName = 'Laboratoire (Origin)';
                                        $caisseColor = 'bg-blue-50 text-blue-700';
                                    } elseif ($log->service->caisse_type === 'urgence' || strpos($srvName, 'urgence') !== false) {
                                        $caisseName = 'Urgences (Origin)';
                                        $caisseColor = 'bg-red-50 text-red-700';
                                    } elseif ($log->service->caisse_type === 'pharmacie' || strpos($srvName, 'pharmacie') !== false) {
                                        $caisseName = 'Pharmacie (Origin)';
                                        $caisseColor = 'bg-emerald-50 text-emerald-700';
                                    } else {
                                        $caisseName = 'Accueil (Origin)';
                                        $caisseColor = 'bg-gray-50 text-gray-600';
                                    }
                                }
                            @endphp
                            <span class="px-2 py-1 rounded {{ $caisseColor }} text-[10px] font-black uppercase">{{ $caisseName }}</span>
                        </td>
                        <td class="px-6 py-4">
                             <span class="text-[10px] font-bold text-gray-600 uppercase">{{ $log->service->name ?? 'Général' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-black text-gray-900">{{ number_format($log->total, 0, ',', ' ') }}</td>
                        <td class="px-6 py-4 text-center">
                            @if(Str::contains(strtolower($log->payment_method), ['mobile', 'momo']))
                                <div class="flex flex-col items-center">
                                    <span class="px-2 py-0.5 bg-orange-50 text-orange-600 rounded text-[9px] font-black uppercase border border-orange-100">Mobile</span>
                                    @if($log->payment_operator)
                                        <span class="text-[8px] font-bold text-gray-400 uppercase mt-0.5">{{ $log->payment_operator }}</span>
                                    @endif
                                </div>
                            @elseif(Str::contains(strtolower($log->payment_method), ['assurance']))
                                <div class="flex flex-col items-center">
                                    <span class="px-2 py-0.5 bg-purple-50 text-purple-600 rounded text-[9px] font-black uppercase border border-purple-100">Assurance</span>
                                    @if($log->payment_operator)
                                        <span class="text-[8px] font-bold text-gray-400 uppercase mt-0.5">{{ $log->payment_operator }}</span>
                                    @endif
                                </div>
                            @elseif(Str::contains(strtolower($log->payment_method), ['cash', 'esp']))
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[9px] font-black uppercase border border-emerald-100">Cash</span>
                            @else
                                <span class="px-2 py-0.5 bg-gray-50 text-gray-600 rounded text-[9px] font-black uppercase border border-gray-100">{{ $log->payment_method }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-bold text-gray-500">
                            {{ $log->cashier->name ?? 'Système' }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-12 text-center text-gray-400 italic">Aucune transaction trouvée.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t border-gray-100 bg-gray-50">
        {{ $logs->links() }}
    </div>
</div>
@endsection
