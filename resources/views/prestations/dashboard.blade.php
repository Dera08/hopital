@extends('layouts.app')

@section('title', 'Dashboard Prestations')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
    .stats-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .chart-container {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .recent-item {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }
    .recent-item:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dashboard Prestations</h1>
                    <p class="text-gray-600 mt-1">Vue d'ensemble des prestations médicales</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('prestations.index') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition">
                        Gérer les Prestations
                    </a>
                    <a href="{{ route('prestations.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                        Nouvelle Prestation
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="stats-card">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm font-medium">Total Prestations</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['total_prestations'] }}</p>
                    </div>
                    <div class="text-indigo-200 opacity-80">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 text-sm font-medium">Prestations Actives</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['active_prestations'] }}</p>
                    </div>
                    <div class="text-pink-200 opacity-80">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Revenus Totaux</p>
                        <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} CFA</p>
                    </div>
                    <div class="text-blue-200 opacity-80">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Payantes</p>
                        <p class="text-3xl font-bold mt-1">{{ $stats['payable_prestations'] }}</p>
                    </div>
                    <div class="text-green-200 opacity-80">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Details -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

            <!-- Category Distribution -->
            <div class="chart-container">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Répartition par Catégorie</h3>
                <div class="space-y-3">
                    @foreach($categoryStats as $category => $data)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full bg-indigo-500"></div>
                                <span class="font-medium text-gray-700 capitalize">{{ $category }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-gray-900">{{ $data['count'] }}</span>
                                <span class="text-gray-500 text-sm ml-2">{{ number_format($data['total_price'], 0, ',', ' ') }} CFA</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Service Distribution -->
            <div class="chart-container">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Répartition par Service</h3>
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach($serviceStats as $serviceStat)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full bg-green-500"></div>
                                <span class="font-medium text-gray-700">{{ $serviceStat->service->name }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-gray-900">{{ $serviceStat->count }}</span>
                                <span class="text-gray-500 text-sm ml-2">{{ number_format($serviceStat->total_price, 0, ',', ' ') }} CFA</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent and Top Prestations -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Recent Prestations -->
            <div class="chart-container">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Prestations Récentes</h3>
                <div class="space-y-3">
                    @forelse($recentPrestations as $prestation)
                        <div class="recent-item">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $prestation->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $prestation->service->name }} • {{ ucfirst($prestation->category) }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="font-bold text-indigo-600">{{ number_format($prestation->price, 0, ',', ' ') }} CFA</span>
                                    <p class="text-xs text-gray-500">{{ $prestation->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucune prestation récente</p>
                    @endforelse
                </div>
            </div>

            <!-- Top Prestations -->
            <div class="chart-container">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Prestations les Plus Utilisées</h3>
                <div class="space-y-3">
                    @forelse($topPrestations as $prestation)
                        <div class="recent-item">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $prestation->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $prestation->service->name }} • {{ $prestation->appointments_count }} utilisations</p>
                                </div>
                                <div class="text-right">
                                    <span class="font-bold text-green-600">{{ number_format($prestation->price, 0, ',', ' ') }} CFA</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">Aucune donnée d'utilisation</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Chart Placeholder -->
        <div class="chart-container mt-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Évolution des Revenus Annuels</h3>
            <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-gray-500">Graphique des revenus mensuels</p>
                    <p class="text-sm text-gray-400 mt-1">Intégration Chart.js à venir</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Future implementation for charts
document.addEventListener('DOMContentLoaded', function() {
    // Monthly revenue chart can be implemented here
    const monthlyRevenue = @json($monthlyRevenue);
    console.log('Monthly Revenue Data:', monthlyRevenue);
});
</script>
@endpush
