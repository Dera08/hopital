@extends('layouts.app')

@section('title', 'Clôture de Caisse')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-gray-900 tracking-tight">Clôture de Caisse</h1>
        <p class="text-sm font-bold text-gray-500 mt-1 uppercase tracking-widest">{{ Auth::user()->name }} - {{ now()->format('d F Y') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <!-- RECAP MOBILE MONEY -->
        <div class="bg-gradient-to-br from-orange-50 to-orange-100 p-8 rounded-3xl shadow-sm border border-orange-200 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6 opacity-10">
                <i class="fas fa-mobile-alt text-9xl text-orange-600"></i>
            </div>
            <div class="relative z-10">
                <h3 class="text-xs font-black text-orange-600 uppercase tracking-widest mb-2">Mobile Money (Automatique)</h3>
                <p class="text-4xl font-black text-gray-900 mb-1">{{ number_format($mobileMoneyTotal, 0, ',', ' ') }} <span class="text-lg text-gray-500">FCFA</span></p>
                <div class="mt-4 inline-flex items-center bg-white/80 backdrop-blur px-3 py-1.5 rounded-lg text-xs font-bold text-orange-600 shadow-sm">
                    <i class="fas fa-check-circle mr-2"></i> Transféré automatiquement à l'Admin
                </div>
            </div>
        </div>

        <!-- RECAP ESPÈCES -->
        <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 p-8 rounded-3xl shadow-sm border border-emerald-200 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6 opacity-10">
                <i class="fas fa-wallet text-9xl text-emerald-600"></i>
            </div>
            <div class="relative z-10">
                <h3 class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-2">Espèces (Dans votre Caisse)</h3>
                <p class="text-4xl font-black text-gray-900 mb-1">{{ number_format($cashTotal, 0, ',', ' ') }} <span class="text-lg text-gray-500">FCFA</span></p>
                
                @if($existingTransfer)
                    <div class="mt-6">
                        <div class="bg-white/90 backdrop-blur p-4 rounded-xl border border-emerald-200 shadow-sm">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 mr-3">
                                    <i class="fas fa-paper-plane"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-gray-400 uppercase">Statut du versement</p>
                                    <p class="text-sm font-bold text-gray-900">
                                        @if($existingTransfer->status === 'pending')
                                            <span class="text-orange-500">En attente de validation Admin</span>
                                        @else
                                            <span class="text-emerald-600">Validé par l'Admin</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($cashTotal > 0)
                    <form action="{{ route('cashier.transfer.store') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="amount" value="{{ $cashTotal }}">
                        <button type="submit" class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold shadow-lg shadow-emerald-200 transition-all transform hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center">
                            <i class="fas fa-money-bill-wave mr-2"></i> Transférer le Cash à l'Admin
                        </button>
                        <p class="text-[10px] text-emerald-700 mt-2 text-center font-bold">
                            * Une demande sera envoyée à l'administrateur
                        </p>
                    </form>
                @else
                    <div class="mt-6 p-4 bg-white/60 rounded-xl text-center text-sm font-bold text-gray-500">
                        Aucune espèce à transférer pour le moment.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- HISTORIQUE RÉCENT -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50">
            <h3 class="font-black text-gray-900 uppercase tracking-widest text-sm">Vos Derniers Versements</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider text-right">Montant</th>
                        <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider text-center">Statut</th>
                        <th class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-wider text-center">Validé le</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentTransfers as $transfer)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-600">
                                {{ $transfer->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-black text-gray-900">
                                {{ number_format($transfer->amount, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($transfer->status === 'pending')
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-bold">En attente</span>
                                @elseif($transfer->status === 'confirmed')
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded text-xs font-bold">Validé</span>
                                @else
                                    <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-bold">Rejeté</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-xs font-bold text-gray-500">
                                {{ $transfer->validated_at ? $transfer->validated_at->format('d/m/Y H:i') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 text-sm font-medium">Aucun historique récent.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
