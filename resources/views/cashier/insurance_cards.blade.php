@extends('layouts.cashier_layout')

@section('content')
<div class="p-4 sm:p-8 bg-gray-50 min-h-screen">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h2 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tight">Cartes d'Assurance</h2>
            <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">Liste des cartes d'assurance enregistrées via les factures</p>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <button class="bg-indigo-600 text-white px-6 py-3 rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 flex items-center gap-3 font-black transition-all flex-1 md:flex-none justify-center">
                <i class="fas fa-file-export"></i> Exporter la liste
            </button>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <i class="fas fa-id-card text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Cartes Enregistrées</p>
                <p class="text-2xl font-black text-gray-900">{{ $invoices->total() }} <small class="text-xs text-gray-400 font-bold">cartes</small></p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fas fa-shield-alt text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Part Assurance Total</p>
                <p class="text-2xl font-black text-gray-900">NSIA / Autre</p>
            </div>
        </div>
        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 flex items-center space-x-6">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="fas fa-user-check text-2xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Dernière Utilisation</p>
                <p class="text-lg font-black text-gray-900">{{ $invoices->first()?->created_at->diffForHumans() ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50 border-b border-gray-50">
                    <tr>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Client / Patient</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Compagnie</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">N° de Carte</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Taux</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">Dernière Facture</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($invoices as $inv)
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="px-8 py-5">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-400 font-black">
                                        {{ substr($inv->patient?->name ?? 'P', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-black text-gray-800 text-sm">{{ $inv->patient?->name ?? 'Patient Supprimé' }}</p>
                                        <p class="text-[10px] font-bold text-gray-400">IPU: {{ $inv->patient?->ipu ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <span class="bg-purple-100 text-purple-700 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest">
                                    {{ $inv->insurance_name ?? ($inv->payment_operator ?: 'NON SPÉCIFIÉ') }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="font-mono text-xs font-bold text-gray-600 bg-gray-100 px-2 py-1 rounded">
                                    {{ $inv->insurance_card_number ?? '-----' }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-black">
                                    {{ $inv->insurance_coverage_rate }}%
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-sm font-black text-gray-800">#{{ $inv->invoice_number }}</p>
                                <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $inv->created_at->format('d/m/Y') }}</p>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('cashier.invoices.show', $inv->id) }}" class="p-2.5 bg-gray-100 text-gray-500 rounded-xl hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-id-card text-gray-200 text-6xl mb-4"></i>
                                    <p class="text-gray-400 font-bold">Aucune carte d'assurance trouvée</p>
                                    <p class="text-xs text-gray-300 mt-1">Les cartes s'affichent ici dès qu'un paiement avec assurance est effectué</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($invoices->hasPages())
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100">
                {{ $invoices->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
