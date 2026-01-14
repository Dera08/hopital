@extends('layouts.app')

@section('title', 'Tableau de Bord - HospitSIS')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header avec informations utilisateur -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        Bienvenue, Dr. {{ Auth::user()?->name ?? 'User' }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ Auth::user()?->service?->name ?? 'Service non assigné' }} •
                        <span class="capitalize">{{ Auth::user()?->role ?? 'user' }}</span>
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">
                        {{ now()->isoFormat('dddd D MMMM YYYY') }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 mr-2 bg-green-400 rounded-full animate-pulse"></span>
                        En ligne
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Statistiques KPI -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Patients Actifs -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Patients Actifs</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_patients'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="text-green-600">+12%</span> vs mois dernier
                        </p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rendez-vous Aujourd'hui -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">RDV Aujourd'hui</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['today_appointments'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $stats['pending_appointments'] }} en attente
                        </p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Lits Disponibles -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Lits Disponibles</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['available_beds'] }}/{{ $stats['total_beds'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            Taux d'occupation: {{ $stats['occupancy_rate'] }}%
                        </p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Alertes Cliniques -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Alertes Actives</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['active_alerts'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $stats['critical_alerts'] }} critiques
                        </p>
                    </div>
                    <div class="bg-red-100 rounded-full p-3">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Colonne Principale (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Rendez-vous du jour -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">Rendez-vous du Jour</h2>
                        <a href="{{ route('appointments.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Voir tout →
                        </a>
                    </div>
                    <div class="p-6">
                        @forelse($todayAppointments as $appointment)
                        <div class="flex items-center justify-between py-4 border-b last:border-b-0">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-blue-600 font-semibold">
                                            {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->name, 0, 1) }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $appointment->patient->full_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $appointment->patient->ipu }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $appointment->reason ?? 'Consultation' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $appointment->appointment_datetime->format('H:i') }}
                                </p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($appointment->status === 'confirmed') bg-green-100 text-green-800
                                    @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Aucun rendez-vous prévu aujourd'hui</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Admissions Récentes -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">Admissions Actives</h2>
                        <a href="{{ route('admissions.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Voir tout →
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Chambre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Admission</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($activeAdmissions as $admission)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $admission->patient->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $admission->patient->ipu }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $admission->room->room_number ?? 'Non assigné' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $admission->admission_date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ ucfirst($admission->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                        Aucune admission active
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Colonne Latérale (1/3) -->
            <div class="space-y-6">
                
                <!-- Alertes Cliniques -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Alertes Cliniques</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse($clinicalAlerts as $alert)
                        <div class="flex items-start space-x-3 p-3 rounded-lg
                            @if($alert->severity === 'critical') bg-red-50 border border-red-200
                            @elseif($alert->severity === 'high') bg-orange-50 border border-orange-200
                            @else bg-yellow-50 border border-yellow-200 @endif">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 
                                    @if($alert->severity === 'critical') text-red-600
                                    @elseif($alert->severity === 'high') text-orange-600
                                    @else text-yellow-600 @endif" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $alert->patient->full_name }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $alert->message }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $alert->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="mt-2 text-sm text-gray-500">Aucune alerte active</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Occupation des Lits -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-semibold text-gray-900">Occupation des Lits</h2>
                        <a href="{{ route('rooms.bed-management') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Gérer →
                        </a>
                    </div>
                    <div class="p-6">
                        <canvas id="bedOccupancyChart" height="200"></canvas>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Actions Rapides</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('patients.create') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg font-medium transition">
                            + Nouveau Patient
                        </a>
                        <a href="{{ route('admissions.create') }}" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-4 rounded-lg font-medium transition">
                            + Nouvelle Admission
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique d'occupation des lits
    const ctx = document.getElementById('bedOccupancyChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Occupés', 'Disponibles', 'Nettoyage'],
            datasets: [{
                data: [{{ $stats['occupied_beds'] }}, {{ $stats['available_beds'] }}, {{ $stats['cleaning_beds'] }}],
                backgroundColor: ['#EF4444', '#10B981', '#F59E0B']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endsection