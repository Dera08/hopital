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
            <h1 class="text-2xl font-bold text-gray-900">Modifier le Dossier Médical : {{ $record->patient_name }}</h1>
            <a href="{{ route('medical-records.show', $record->id) }}" class="text-blue-600 hover:underline">← Retour au dossier</a>
        </div>

        <div class="bg-white shadow rounded-xl p-6 space-y-6">
            <form action="{{ route('medical_records.update', $record->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-500"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
                    Modification des constantes & Motif
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
                        <label class="block text-sm font-bold text-blue-900 mb-2">Observations (Résultats de Tests & Diagnostic)</label>
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

                    {{-- VISIBILITÉ AU PATIENT --}}
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="is_visible_to_patient" value="1" {{ old('is_visible_to_patient', $record->is_visible_to_patient) ? 'checked' : '' }} id="visibility" class="rounded">
                        <label for="visibility" class="text-sm font-bold text-blue-900">Rendre visible au patient</label>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-lg shadow-lg flex items-center justify-center gap-2 transition-all transform active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                        Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
