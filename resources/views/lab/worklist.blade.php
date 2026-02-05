@extends('layouts.app')

@section('title', 'Analyses en Cours - Laboratoire')

@section('content')
<div class="px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Analyses en cours</h1>
        <p class="text-gray-500 mt-1">Liste de travail - Échantillons reçus et en cours de traitement</p>
    </div>

    <!-- Filtres -->
    <div class="mb-6 flex gap-4">
        <a href="?filter=all" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('filter') === 'all' ? 'bg-teal-600 text-white shadow-lg shadow-teal-500/30' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
            Tout voir
        </a>
        <a href="?filter=urgent" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('filter') === 'urgent' ? 'bg-red-600 text-white shadow-lg shadow-red-500/30' : 'bg-white text-gray-700 hover:bg-gray-50' }}">
            Urgences
        </a>
    </div>

    @if($pendingRequests->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-teal-50 mb-6">
                <svg class="w-10 h-10 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Aucune analyse en attente</h3>
            <p class="text-gray-500 max-w-sm mx-auto">Toutes les demandes ont été traitées ou aucun échantillon n'a été reçu pour le moment.</p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6">
            @foreach($pendingRequests as $request)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                    {{ $request->test_name }}
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-50 text-teal-700">
                                        {{ $request->test_category }}
                                    </span>
                                </h3>
                                <div class="mt-1 flex items-center gap-2 text-sm text-gray-500">
                                    <span class="font-medium text-gray-900">{{ $request->patient_name }}</span>
                                    <span>•</span>
                                    <span class="font-mono text-xs bg-gray-100 px-1.5 py-0.5 rounded">IPU: {{ $request->patient_ipu }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            @if($request->status === 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700 uppercase tracking-wide">
                                    En attente d'échantillon
                                </span>
                            @elseif($request->status === 'sample_received')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 uppercase tracking-wide">
                                    Échantillon Reçu
                                </span>
                            @elseif($request->status === 'in_progress')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 uppercase tracking-wide animate-pulse">
                                    En Cours
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Informations Cliniques</p>
                                <p class="text-sm text-gray-700">{{ $request->clinical_info ?? 'Aucune information clinique fournie.' }}</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 flex items-center justify-between text-xs text-gray-500">
                            <p>Prescrit par Dr. {{ $request->doctor->name ?? 'Inconnu' }}</p>
                            <p>Demandé le {{ $request->requested_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div class="flex items-center justify-end gap-3">
                        @if($request->status === 'pending')
                            <form action="{{ route('lab.requests.status', $request->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="sample_received">
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-lg shadow-blue-500/30 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Confirmer Réception Échantillon
                                </button>
                            </form>
                        @elseif($request->status === 'sample_received')
                            <form action="{{ route('lab.requests.status', $request->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="in_progress">
                                <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition shadow-lg shadow-purple-500/30 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                    Démarrer l'Analyse
                                </button>
                            </form>
                        @elseif($request->status === 'in_progress')
                            <button onclick="document.getElementById('resultModal{{ $request->id }}').classList.remove('hidden')" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium rounded-lg transition shadow-lg shadow-teal-500/30 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Saisir le Résultat
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- MODAL RESULTAT -->
            <div id="resultModal{{ $request->id }}" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900">Saisie des résultats</h3>
                        <button onclick="document.getElementById('resultModal{{ $request->id }}').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    
                    <form action="{{ route('lab.requests.result', $request->id) }}" method="POST" class="p-6">
                        @csrf
                        <div class="mb-4 bg-blue-50 border border-blue-100 rounded-lg p-4">
                            <p class="text-sm text-blue-800 font-medium">Examen: <span class="font-bold">{{ $request->test_name }}</span></p>
                            <p class="text-sm text-blue-600">Patient: {{ $request->patient_name }}</p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Résultat / Conclusion</label>
                                <textarea name="result" rows="4" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500" placeholder="Saisir le résultat complet ici..."></textarea>
                            </div>

                            <!-- Champs dynamiques si besoin (placeholder pour fonctionnalités futures) -->
                            <div class="p-4 border border-dashed border-gray-300 rounded-lg bg-gray-50 text-center">
                                <p class="text-xs text-gray-500">Possibilité d'ajouter des fichiers joints ici (PDF, Images graphiques)</p>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3">
                            <button type="button" onclick="document.getElementById('resultModal{{ $request->id }}').classList.add('hidden')" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium hover:bg-gray-50">
                                Annuler
                            </button>
                            <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg font-medium hover:bg-teal-700 shadow-lg shadow-teal-500/30">
                                Valider et Envoyer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
