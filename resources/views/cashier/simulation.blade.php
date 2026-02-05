@extends('layouts.cashier_layout')

@section('title', 'Simulation de Paiement - Caisse')

@section('content')
<div class="h-full flex items-center justify-center p-6">
    <div class="w-full max-w-lg">
        <!-- Security Badge -->
        <div class="text-center mb-6">
            <div class="d-inline-flex align-items-center justify-content-center bg-white px-4 py-2 rounded-full shadow-sm text-green-600 border border-green-100">
                <i class="fas fa-shield-alt mr-2 text-lg"></i>
                <span class="font-bold tracking-wide text-sm uppercase">Paiement Sécurisé</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] animate-pulse"></div>
                <h5 class="text-blue-100 text-xs font-bold uppercase tracking-widest mb-2 relative z-10">Total à Payer</h5>
                <div class="text-white text-5xl font-extrabold relative z-10 tracking-tight">
                    {{ number_format($amount ?? 0, 0, ',', ' ') }} <span class="text-2xl font-medium opacity-80">FCFA</span>
                </div>
            </div>

            <!-- Body -->
            <div class="p-8">
                
                <!-- Operator Info -->
                <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-100 border-dashed">
                    <div>
                        <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">OPÉRATEUR</span>
                        <span class="flex items-center">
                            <span class="bg-gray-900 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                            {{ str_contains(strtolower($operator), 'money') ? $operator : 'Mobile Money (' . ucfirst($operator) . ')' }}
                        </span>
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">DATE</span>
                        <span class="text-sm font-semibold text-gray-800">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <!-- Transaction Details -->
                <div class="bg-gray-50 rounded-2xl p-5 mb-6 border border-gray-200/60">
                    <div class="flex justify-between mb-3">
                        <span class="text-xs text-gray-500 font-medium uppercase tracking-wide">ID Transaction</span>
                        <span class="font-mono font-bold text-gray-800 text-sm bg-white px-2 py-0.5 rounded border border-gray-200">{{ $transactionId }}</span>
                    </div>
                    <div class="flex justify-between mb-3">
                        <span class="text-xs text-gray-500 font-medium uppercase tracking-wide">Bénéficiaire</span>
                        <span class="font-bold text-gray-900 text-sm">Clinique Médicale Saint-Jean</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-500 font-medium uppercase tracking-wide">Motif</span>
                        <span class="font-semibold text-blue-600 text-sm">
                            @if(str_starts_with($transactionId, 'APT-'))
                                Rendez-vous Médical
                            @elseif(str_starts_with($transactionId, 'LAB-'))
                                Analyse de Laboratoire
                            @else
                                Consultation Sans RDV
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Simulation Alert -->
                <div class="flex items-center p-4 mb-6 bg-yellow-50 rounded-xl border border-yellow-100 text-yellow-800">
                    <i class="fas fa-exclamation-triangle text-xl mr-4 text-yellow-500"></i>
                    <div class="text-xs leading-relaxed">
                        <strong class="block mb-0.5 text-yellow-900 uppercase">Mode Simulation</strong>
                        Ceci est un environnement de test. Aucune somme réelle ne sera débitée de votre compte.
                    </div>
                </div>

                <!-- Actions -->
                <form action="{{ route('simulation.payment.validate', ['id' => $transactionId]) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold shadow-lg shadow-green-500/30 hover:shadow-green-500/40 hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center text-sm uppercase tracking-wider mb-4 group">
                        <i class="fas fa-check-circle mr-2 text-lg group-hover:scale-110 transition-transform"></i> Confirmer le paiement
                    </button>
                    
                    <a href="{{ route('cashier.walk-in.index') }}" class="block w-full py-3 text-center text-gray-400 text-sm font-semibold hover:text-gray-600 hover:bg-gray-50 rounded-xl transition-colors">
                        <i class="fas fa-times-circle mr-1"></i> Annuler la transaction
                    </a>
                </form>
            </div>
            
            <!-- Footer Info -->
            <div class="bg-gray-50 text-center py-3 border-t border-gray-100">
                <div class="flex items-center justify-center space-x-6 text-[10px] uppercase font-bold text-gray-400 tracking-widest">
                    <span class="flex items-center"><i class="fas fa-lock mr-1.5"></i> SSL Encrypted</span>
                    <span class="flex items-center"><i class="fas fa-bolt mr-1.5"></i> Instantané</span>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-6 flex justify-center space-x-4 opacity-40 grayscale hover:grayscale-0 transition-all duration-500">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/Mtn_logo.svg/2048px-Mtn_logo.svg.png" alt="MTN" class="h-5">
            <img src="https://botte-cotedivoire.com/wp-content/uploads/2023/12/Orange-Money-Logo-1-1024x1024.png" alt="Orange" class="h-5">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/Mtn_logo.svg/2048px-Mtn_logo.svg.png" alt="Moov" class="h-5">
        </div>
    </div>
</div>
@endsection
