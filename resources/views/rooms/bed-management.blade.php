@extends('layouts.app')

@section('title', 'Gestion des Lits')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Chambres et Lits</h1>
            <p class="text-sm text-gray-500">Visualisation en temps réel des disponibilités par service.</p>
        </div>
        {{-- CORRECTION ICI : Le bouton devient un lien vers la route create --}}
        <a href="{{ route('rooms.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm inline-block">
            + Ajouter une chambre
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($rooms as $room)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700">Chambre #{{ $room->room_number }}</h3>
                    <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-600">
                        {{ $room->service->name ?? 'Général' }}
                    </span>
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between text-sm mb-4">
                        <span class="text-gray-500">Capacité :</span>
                        <span class="font-semibold text-gray-800">{{ $room->capacity }} lits</span>
                    </div>
                    
                    {{-- Affichage des lits réels --}}
                    <div class="flex flex-wrap gap-2">
                        @foreach($room->beds as $bed)
                            <div title="{{ $bed->bed_tag }}" class="w-8 h-8 rounded border-2 flex items-center justify-center 
                                {{ $bed->status === 'occupied' ? 'bg-red-500 border-red-600 text-white' : 'bg-gray-100 border-gray-300 text-gray-400' }}">
                                <i class="fas fa-bed text-xs"></i>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="bg-gray-50 p-3 border-t border-gray-100 flex justify-end">
                    <a href="{{ route('rooms.show', $room->id) }}" class="text-blue-600 text-xs font-bold hover:underline">Détails de l'occupation →</a>
                </div>
            </div>
        @empty
            <div class="col-span-full p-12 text-center bg-white rounded-xl border-2 border-dashed border-gray-300">
                <p class="text-gray-500 italic">Aucune chambre configurée.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection