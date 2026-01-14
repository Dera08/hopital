{{-- resources/views/admissions/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Gestion des Admissions')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Admissions</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $admissions->total() }} admissions</p>
            </div>
            <a href="{{ route('admissions.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouvelle Admission
            </a>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm font-medium">Admissions Actives</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['active'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm font-medium">Aujourd'hui</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['today'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-orange-100 rounded-lg p-3">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <p class="text-gray-500 text-sm font-medium">En attente sortie</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_discharge'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table des admissions -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Chambre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Médecin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date admission</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($admissions as $admission)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $admission->patient->full_name }}</div>
                            <div class="text-sm text-gray-500">{{ $admission->patient->ipu }}</div>
                        </td>
                       <td class="px-6 py-4 whitespace-nowrap">
    <div class="text-sm font-medium text-gray-900">
        {{ $admission->room->room_number ?? 'Non assigné' }}
    </div>
    <div class="text-xs text-blue-600 flex items-center mt-1">
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M3 10h18M3 14h18m-9-4v8m-7 0V6a2 2 0 012-2h10a2 2 0 012 2v12"></path>
        </svg>
        {{ $admission->bed->bed_number ?? 'Lit non spécifié' }}
    </div>
</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $admission->room->service->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $admission->doctor->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $admission->admission_date->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-500">{{ $admission->admission_date->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($admission->status === 'active') bg-green-100 text-green-800
                                @elseif($admission->status === 'discharged') bg-gray-100 text-gray-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($admission->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('admissions.show', $admission) }}" class="text-blue-600 hover:text-blue-900">
                                    Voir
                                </a>
                                @if($admission->status === 'active')
                                <a href="{{ route('admissions.edit', $admission) }}" class="text-yellow-600 hover:text-yellow-900">
                                    Modifier
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            Aucune admission trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            @if($admissions->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t">
                {{ $admissions->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection