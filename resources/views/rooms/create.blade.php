@extends('layouts.app')

@section('title', 'Ajouter une Chambre')

@section('content')
<div class="p-6">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Créer une Nouvelle Chambre</h1>

        <form action="{{ route('rooms.store') }}" method="POST">
            @csrf
            <div class="space-y-5">
                {{-- Numéro de chambre --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Identifiant / Numéro de chambre</label>
                    <input type="text" name="room_number" required placeholder="Ex: CH-101"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                {{-- Sélection du Service --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Service médical</label>
                    <select name="service_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none bg-white">
                        <option value="">-- Sélectionner le service --</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Capacité --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nombre de lits dans cette chambre</label>
                    <input type="number" name="capacity" required min="1" max="12" value="1"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg outline-none">
                    <p class="text-xs text-gray-400 mt-2 italic">Note : Le système générera automatiquement chaque lit individuellement.</p>
                </div>

                <div class="pt-6 flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 shadow-lg transition">
                        Enregistrer la Chambre
                    </button>
                    <a href="{{ route('rooms.bed-management') }}" class="flex-1 text-center bg-gray-100 text-gray-600 font-bold py-3 rounded-xl hover:bg-gray-200 transition">
                        Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection