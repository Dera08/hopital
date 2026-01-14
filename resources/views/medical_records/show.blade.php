@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        {{-- Alerte de succès --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Détails du Patient : {{ $record->patient_name }}</h1>
            <a href="{{ route('medical_records.index') }}" class="text-blue-600 hover:underline">← Retour à la liste</a>
        </div>

        <div class="bg-white shadow rounded-xl p-6 space-y-6">
            {{-- BLOC ACTIONS : STYLE ICONES CIRCULAIRES --}}
            <div class="mb-6 p-4 bg-gray-50 border border-gray-100 rounded-xl">
                <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-3">Actions rapides</h4>
                <div class="flex items-center gap-3">
                    
                    {{-- Bouton Partager (Vert) --}}
                    <form action="{{ route('medical_records.share', $record->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" title="Partager au patient" class="w-10 h-10 flex items-center justify-center bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg shadow-md transition-all active:scale-90">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13"></line>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                            </svg>
                        </button>
                    </form>

                    {{-- Bouton Modifier (Bleu) --}}
                    <a href="#formulaire-constantes" title="Modifier dans le carnet" class="w-10 h-10 flex items-center justify-center bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow-md transition-all active:scale-90">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                    </a>

                    {{-- Bouton Supprimer (Rouge) --}}
                    <form action="#" method="POST" class="inline" onsubmit="return confirm('Voulez-vous supprimer cet enregistrement ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Supprimer" class="w-10 h-10 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-md transition-all active:scale-90">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            {{-- INFOS DE BASE (Identité) --}}
            <div class="grid grid-cols-2 gap-4 border-b pb-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold">IPU du Patient</p>
                    <p class="text-lg font-medium text-gray-800">{{ $record->patient_ipu }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold">Niveau d'Urgence</p>
                    <span class="px-3 py-1 text-xs font-bold rounded-full {{ $record->urgency === 'critique' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ strtoupper($record->urgency) }}
                    </span>
                </div>
            </div>

            {{-- FORMULAIRE DE MISE À JOUR (Constantes + Diagnostic) --}}
            <form id="formulaire-constantes" action="{{ route('medical_records.update', $record->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    Vérification des constantes & Motif
                </h3>

                {{-- CONSTANTES VITALES --}}
                <div class="grid grid-cols-3 gap-6 py-4 bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="text-center">
                        <label class="block text-sm text-gray-500 mb-1">Température (°C)</label>
                        <input type="text" name="temperature" value="{{ old('temperature', $record->temperature) }}" 
                            class="w-full text-center text-xl font-bold text-orange-600 border border-gray-300 rounded-md focus:ring-2 focus:ring-orange-400 outline-none">
                    </div>
                    <div class="text-center border-x border-gray-200">
                        <label class="block text-sm text-gray-500 mb-1">Tension Artérielle</label>
                        <input type="text" name="blood_pressure" value="{{ old('blood_pressure', $record->blood_pressure) }}" 
                            class="w-full text-center text-xl font-bold text-blue-600 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 outline-none">
                    </div>
                    <div class="text-center">
                        <label class="block text-sm text-gray-500 mb-1">Pouls (BPM)</label>
                        <input type="text" name="pulse" value="{{ old('pulse', $record->pulse) }}" 
                            class="w-full text-center text-xl font-bold text-emerald-600 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-400 outline-none">
                    </div>
                </div>

                {{-- MOTIF --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-800 underline mb-2">Motif de consultation :</label>
                    <textarea name="reason" rows="2" 
                        class="w-full p-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 outline-none shadow-sm">{{ old('reason', $record->reason) }}</textarea>
                </div>

                <hr class="border-gray-100 my-6">

                {{-- ESPACE PRESCRIPTION --}}
                <div class="bg-blue-50 p-6 rounded-xl border border-blue-100 space-y-4 shadow-inner">
                    <h3 class="font-bold text-blue-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Espace Prescription & Diagnostic
                    </h3>
                    
                    <div>
                        <label class="block text-sm font-bold text-blue-900 mb-2">Observations (Diagnostic)</label>
                        <textarea name="observations" rows="3" 
                            class="w-full p-3 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white" 
                            placeholder="Ex: TDR Palu Positif...">{{ old('observations', $record->observations) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-blue-900 mb-2">Ordonnance Digitale</label>
                        <textarea name="ordonnance" rows="5" 
                            class="w-full p-3 border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white" 
                            placeholder="1. Médicament A...">{{ old('ordonnance', $record->ordonnance) }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-lg shadow-lg flex items-center justify-center gap-2 transition-all transform active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>

            {{-- DÉCISION D'ADMISSION --}}
            @if($record->status !== 'admitted')
            <div class="mt-6 pt-6 border-t border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500"><path d="M3 7v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z"/><path d="M8 5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2H8V5z"/></svg>
                    Décision d'Admission
                </h3>

                <form action="{{ route('medical_records.admit', $record->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-800 mb-2">Sélectionner un lit disponible</label>
                        <select name="bed_id" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white" required>
                            <option value="">Choisir un lit...</option>
                            @foreach($availableBeds as $bed)
                                @if($bed->room)
                                    <option value="{{ $bed->id }}">Chambre {{ $bed->room->room_number }} - Lit {{ $bed->bed_number }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl shadow-lg flex items-center justify-center gap-3 transition-all transform active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                        Admettre le Patient
                    </button>
                </form>
            </div>
            @endif

            {{-- BOUTON TERMINER --}}
            <div class="mt-6 pt-6 border-t border-gray-100">
                <form action="{{ route('medical_records.archive', $record->id) }}" method="POST" onsubmit="return confirm('Voulez-vous clôturer ce dossier et l\'envoyer aux archives ?')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-xl shadow-lg flex items-center justify-center gap-3 transition-all transform active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Terminer la consultation & Archiver
                    </button>
                </form>
            </div>

        </div> {{-- Fin du bg-white --}}
    </div>
</div>
@endsection