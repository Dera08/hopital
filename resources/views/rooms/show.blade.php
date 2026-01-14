@extends('layouts.app')

@section('title', 'Détails de la Chambre')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('rooms.bed-management') }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-100">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Chambre #{{ $room->room_number }}</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Infos de la chambre --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            <h2 class="font-bold text-gray-700 mb-4 uppercase text-xs tracking-wider">Informations Générales</h2>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-gray-500">Service :</span>
                    <span class="font-bold text-blue-600">{{ $room->service->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Capacité :</span>
                    <span class="font-medium">{{ $room->bed_capacity }} lits</span>
                </div>
                
                @php 
                    $total = $room->bed_capacity ?: 1; 
                    $occupiedCount = $room->beds->where('status', 'occupied')->count();
                    $percent = ($occupiedCount / $total) * 100;
                @endphp

                <div class="mt-4">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-500">Occupation</span>
                        <span class="font-bold">{{ round($percent) }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Liste des lits --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Lit</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Patient</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($room->beds as $bed)
                    <tr>
                        <td class="px-6 py-4 font-bold text-gray-700">{{ $bed->bed_tag }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-bold rounded-full {{ $bed->status == 'available' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                {{ $bed->status == 'available' ? 'Libre' : 'Occupé' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $bed->patient->name ?? 'Aucun patient' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($bed->status == 'available')
                                <button class="text-blue-600 hover:underline text-sm font-bold">Assigner</button>
                            @else
                                <button class="text-red-600 hover:underline text-sm font-bold">Libérer</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection