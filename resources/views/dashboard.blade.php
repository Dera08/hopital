 @extends('layouts.app')

@section('title', 'Tableau de Bord - HospitSIS')

@section('content')
<div class="min-h-screen bg-[#f8fafc] pb-12">
    <div class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center space-x-6">
                    <div class="h-20 w-20 rounded-[2rem] bg-gradient-to-tr from-blue-600 to-indigo-500 shadow-lg shadow-blue-200 flex items-center justify-center text-white ring-4 ring-blue-50">
                        <span class="text-3xl font-black">{{ strtoupper(substr(Auth::user()?->name ?? 'U', 0, 2)) }}</span>
                    </div>
                    <div>
                        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Bienvenue, {{ Auth::user()?->name ?? 'User' }}</h1>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wider">
                                ⚕️ {{ Auth::user()?->service?->name ?? 'Administration' }}
                            </span>
                            <span class="text-gray-400 font-medium text-sm">• {{ ucfirst(Auth::user()?->role ?? 'user') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-6">
                    <div class="text-right hidden sm:block border-r border-gray-100 pr-6">
                        <p class="text-lg font-black text-gray-800">{{ now()->format('H:i') }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ now()->isoFormat('dddd D MMMM') }}</p>
                    </div>
                    <span class="inline-flex items-center px-4 py-2 rounded-2xl text-sm font-bold bg-green-50 text-green-700 ring-1 ring-green-100">
                        <span class="w-2 h-2 mr-2 bg-green-500 rounded-full animate-ping"></span>
                        Système Actif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 mt-10">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-gray-50 hover:shadow-xl transition-all duration-300 group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 rounded-2xl group-hover:bg-blue-600 transition-colors duration-300">
                        <svg class="w-8 h-8 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="text-green-500 text-xs font-black">+12%</span>
                </div>
                <p class="text-gray-400 text-xs font-black uppercase tracking-widest">Patients Actifs</p>
                <p class="text-4xl font-black text-gray-900 mt-1">{{ $stats['active_patients'] }}</p>
            </div>

            <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-gray-50 hover:shadow-xl transition-all duration-300 group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-purple-50 rounded-2xl group-hover:bg-purple-600 transition-colors duration-300">
                        <svg class="w-8 h-8 text-purple-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-xs font-black uppercase tracking-widest">RDV Aujourd'hui</p>
                <p class="text-4xl font-black text-gray-900 mt-1">{{ $stats['today_appointments'] }}</p>
                <p class="text-xs font-bold text-purple-500 mt-2">{{ $stats['pending_appointments'] }} en attente</p>
            </div>

            <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-gray-50 hover:shadow-xl transition-all duration-300">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-emerald-50 rounded-2xl">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black text-gray-400 uppercase">Occupation</p>
                        <p class="text-sm font-black text-emerald-600">{{ $stats['occupancy_rate'] }}%</p>
                    </div>
                </div>
                <p class="text-gray-400 text-xs font-black uppercase tracking-widest">Lits Disponibles</p>
                <p class="text-4xl font-black text-gray-900 mt-1">{{ $stats['available_beds'] }}<span class="text-lg text-gray-300 font-medium">/{{ $stats['total_beds'] }}</span></p>
            </div>

            <div class="bg-white rounded-[2rem] p-7 shadow-sm border border-gray-50 hover:shadow-xl transition-all duration-300 {{ $stats['critical_alerts'] > 0 ? 'ring-2 ring-red-100' : '' }}">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 {{ $stats['critical_alerts'] > 0 ? 'bg-red-500 text-white animate-pulse' : 'bg-red-50 text-red-600' }} rounded-2xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                </div>
                <p class="text-gray-400 text-xs font-black uppercase tracking-widest">Alertes Actives</p>
                <p class="text-4xl font-black text-gray-900 mt-1">{{ $stats['active_alerts'] }}</p>
                <p class="text-xs font-bold text-red-500 mt-2">{{ $stats['critical_alerts'] }} critiques</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mb-10">
            <div class="lg:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-50">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl font-black text-gray-800 tracking-tight">Occupation par Service</h3>
                    <select class="bg-gray-50 border-none rounded-xl text-xs font-bold text-gray-500 focus:ring-0">
                        <option>7 derniers jours</option>
                        <option>30 jours</option>
                    </select>
                </div>
                <div class="relative h-[300px]">
                    <canvas id="bedOccupancyChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-gray-50">
                <h3 class="text-xl font-black text-gray-800 mb-8 tracking-tight">Actions Rapides</h3>
                <div class="grid grid-cols-1 gap-4">
                    <a href="{{ route('patients.create') }}" class="flex items-center p-4 bg-blue-50 rounded-2xl group hover:bg-blue-600 transition-all duration-300">
                        <div class="p-3 bg-white rounded-xl shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <span class="ml-4 font-black text-blue-900 group-hover:text-white transition-colors text-sm">Nouveau Patient</span>
                    </a>

                    <a href="{{ route('invoices.index') }}" class="flex items-center p-4 bg-emerald-50 rounded-2xl group hover:bg-emerald-600 transition-all duration-300">
                        <div class="p-3 bg-white rounded-xl shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="ml-4 font-black text-emerald-900 group-hover:text-white transition-colors text-sm">Facturation</span>
                    </a>

                    <a href="{{ route('reports.index') }}" class="flex items-center p-4 bg-orange-50 rounded-2xl group hover:bg-orange-600 transition-all duration-300">
                        <div class="p-3 bg-white rounded-xl shadow-sm group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <span class="ml-4 font-black text-orange-900 group-hover:text-white transition-colors text-sm">Rapports</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-sm border border-gray-50">
            <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <h2 class="text-xl font-black text-gray-800">Prochains Rendez-vous</h2>
                <a href="{{ route('appointments.index') }}" class="px-4 py-2 bg-white text-blue-600 rounded-xl text-xs font-black shadow-sm hover:shadow-md transition-all">VOIR TOUT</a>
            </div>
            <div class="p-0">
                <table class="w-full">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Patient</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Heure</th>
                            <th class="px-8 py-4 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Motif</th>
                            <th class="px-8 py-4 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($todayAppointments->take(5) as $appointment)
                        <tr class="hover:bg-blue-50/30 transition-colors group cursor-pointer">
                            <td class="px-8 py-5">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                        {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-black text-gray-900">{{ $appointment->patient->full_name }}</p>
                                        <p class="text-[10px] font-bold text-gray-400">{{ $appointment->patient->ipu }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-sm font-black text-gray-700">
                                {{ $appointment->appointment_datetime->format('H:i') }}
                            </td>
                            <td class="px-8 py-5 text-sm font-medium text-gray-500">
                                {{ $appointment->reason ?? 'Consultation Standard' }}
                            </td>
                            <td class="px-8 py-5 text-right">
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase
                                    @if($appointment->status === 'confirmed') bg-green-100 text-green-700
                                    @elseif($appointment->status === 'scheduled') bg-blue-100 text-blue-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $appointment->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-gray-400 font-bold">Aucun rendez-vous aujourd'hui.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('bedOccupancyChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Cardiologie', 'Pédiatrie', 'Chirurgie', 'Urgences', 'Maternité'],
        datasets: [{
            label: 'Occupés',
            data: [12, 8, 15, 6, 10],
            backgroundColor: '#3b82f6', // Bleu Moderne
            borderRadius: 8,
            barThickness: 20,
        }, {
            label: 'Disponibles',
            data: [3, 4, 2, 8, 5],
            backgroundColor: '#e2e8f0', // Gris Clair
            borderRadius: 8,
            barThickness: 20,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: { usePointStyle: true, font: { weight: 'bold', size: 11 } }
            }
        },
        scales: {
            x: { stacked: true, grid: { display: false }, border: { display: false } },
            y: { stacked: true, grid: { color: '#f1f5f9' }, border: { display: false } }
        }
    }
});
</script>
@endpush
@endsection