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
                        Bienvenue, {{ Auth::user()->name }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ Auth::user()->service->name ?? 'Administration' }} • 
                        <span class="capitalize font-semibold text-blue-600">{{ Auth::user()->role }}</span>
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-right">
                        <p class="text-sm text-gray-500">
                            {{ now()->isoFormat('dddd D MMMM YYYY') }}
                        </p>
                        <p class="text-xs text-gray-400">{{ now()->format('H:i') }}</p>
                    </div>
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
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Patients Actifs</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['active_patients'] }}</p>
                        <p class="text-xs text-green-600 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                            </svg>
                            +12% vs mois dernier
                        </p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-4">
                        <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rendez-vous Aujourd'hui -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">RDV Aujourd'hui</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['today_appointments'] }}</p>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ $stats['pending_appointments'] }} en attente
                        </p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-4">
                        <svg class="w-10 h-10 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Lits Disponibles -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Lits Disponibles</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['available_beds'] }}<span class="text-xl text-gray-500">/{{ $stats['total_beds'] }}</span></p>
                        <p class="text-xs text-gray-500 mt-2">
                            Taux : {{ $stats['occupancy_rate'] }}%
                        </p>
                    </div>
                    <div class="bg-green-100 rounded-full p-4">
                        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Alertes Cliniques -->
            <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-red-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Alertes Actives</p>
                        <p class="text-4xl font-bold text-gray-900 mt-2">{{ $stats['active_alerts'] }}</p>
                        <p class="text-xs text-red-600 mt-2">
                            {{ $stats['critical_alerts'] }} critiques
                        </p>
                    </div>
                    <div class="bg-red-100 rounded-full p-4">
                        <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique et Activité Récente -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            
            <!-- Graphique d'occupation -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Occupation des Lits par Service</h3>
                <canvas id="bedOccupancyChart" height="120"></canvas>
            </div>

            <!-- Activité Récente -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Activité Récente</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 bg-green-400 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 font-medium">Nouveau patient enregistré</p>
                            <p class="text-xs text-gray-500">Il y a 5 minutes</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 bg-blue-400 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 font-medium">RDV confirmé</p>
                            <p class="text-xs text-gray-500">Il y a 12 minutes</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 bg-purple-400 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 font-medium">Facture générée</p>
                            <p class="text-xs text-gray-500">Il y a 23 minutes</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 bg-yellow-400 rounded-full"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900 font-medium">Document validé</p>
                            <p class="text-xs text-gray-500">Il y a 34 minutes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Rendez-vous du jour -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Rendez-vous du Jour</h2>
                    <a href="{{ route('appointments.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Voir tout →
                    </a>
                </div>
                <div class="p-6">
                    @forelse($todayAppointments->take(5) as $appointment)
                    <div class="flex items-center justify-between py-4 border-b last:border-b-0 hover:bg-gray-50 transition">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">
                                        {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $appointment->patient->full_name }}</p>
                                <p class="text-sm text-gray-500">{{ $appointment->patient->ipu }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $appointment->reason ?? 'Consultation' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">
                                {{ $appointment->appointment_datetime->format('H:i') }}
                            </p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1
                                @if($appointment->status === 'confirmed') bg-green-100 text-green-800
                                @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="mt-4 text-sm text-gray-500">Aucun rendez-vous prévu aujourd'hui</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Actions Rapides Administrateur -->
            <div class="bg-white rounded-lg shadow-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Actions Rapides</h2>
                </div>
                <div class="p-6 grid grid-cols-2 gap-4">
                    <a href="{{ route('patients.create') }}" class="group bg-gradient-to-br from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        <p class="font-semibold">Nouveau Patient</p>
                    </a>

                    <a href="{{ route('appointments.create') }}" class="group bg-gradient-to-br from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <p class="font-semibold">Nouveau RDV</p>
                    </a>

                    <a href="{{ route('invoices.index') }}" class="group bg-gradient-to-br from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                        </svg>
                        <p class="font-semibold">Facturation</p>
                    </a>

                    <a href="{{ route('reports.index') }}" class="group bg-gradient-to-br from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <p class="font-semibold">Rapports</p>
                    </a>
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
    type: 'bar',
    data: {
        labels: ['Cardiologie', 'Pédiatrie', 'Chirurgie', 'Urgences', 'Maternité'],
        datasets: [{
            label: 'Occupés',
            data: [12, 8, 15, 6, 10],
            backgroundColor: '#EF4444'
        }, {
            label: 'Disponibles',
            data: [3, 4, 2, 8, 5],
            backgroundColor: '#10B981'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            x: { stacked: true },
            y: { stacked: true, beginAtZero: true }
        },
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});
</script>
@endpush
@endsection  