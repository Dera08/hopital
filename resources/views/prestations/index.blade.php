@extends('layouts.app')

@section('title', 'Prestations')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                    Tableau de Bord des Prestations
                </h2>
                <p class="text-gray-600 mt-1">Gestion complète des services, actes et packs forfaitaires</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('prestations.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow-lg flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nouvelle Prestation / Pack
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="p-6 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg text-white">
                <p class="text-indigo-100 text-sm font-medium">Total Prestations</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['total_prestations'] }}</p>
            </div>

            <div class="p-6 bg-gradient-to-br from-pink-500 to-red-500 rounded-xl shadow-lg text-white">
                <p class="text-pink-100 text-sm font-medium">Actives</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['active_prestations'] }}</p>
            </div>

            <div class="p-6 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl shadow-lg text-white">
                <p class="text-blue-100 text-sm font-medium">Revenu Encaissé (Réel)</p>
                <p class="text-3xl font-bold mt-1">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} <span class="text-sm">CFA</span></p>
            </div>

            <div class="p-6 bg-gradient-to-br from-orange-500 to-yellow-500 rounded-xl shadow-lg text-white">
                <p class="text-orange-100 text-sm font-medium">Packs Actifs</p>
                <p class="text-3xl font-bold mt-1">{{ $prestationPacks->count() }}</p>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 mb-8">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Catalogue des Actes</h3>
                <span class="text-xs font-medium text-gray-400 uppercase tracking-widest">Point n°1 & 2 : Suivi & Workflow</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type / Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Workflow / Timing</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prix</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($prestations as $prestation)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($prestation->is_pack)
                                            <span class="w-2 h-2 rounded-full bg-orange-500 mr-2" title="Ceci est un pack"></span>
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-blue-500 mr-2" title="Acte simple"></span>
                                        @endif
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">{{ $prestation->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $prestation->code }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <div class="flex flex-col">
                                        <span>{{ $prestation->service ? $prestation->service->name : 'Service non défini' }}</span>
                                        @if($prestation->service && $prestation->service->is_caisse)
                                            <span class="text-[10px] text-orange-500 font-bold uppercase tracking-tighter">Caisse (Collecteur)</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded {{ $prestation->payment_timing == 'before' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                                            Paye: {{ $prestation->payment_timing }}
                                        </span>
                                        @if($prestation->is_emergency)
                                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-red-100 text-red-700 text-center">Urgent</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                    {{ number_format($prestation->price, 0, ',', ' ') }} <span class="text-[10px] text-gray-400">CFA</span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <a href="{{ route('prestations.edit', $prestation) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">Gérer</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if($prestationPacks->count() > 0)
        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Détail des Packs Forfaitaires
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($prestationPacks as $pack)
                    <div class="bg-white p-6 rounded-xl border border-orange-100 shadow-sm hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $pack->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $pack->description }}</p>
                            </div>
                            <span class="bg-orange-100 text-orange-700 text-xs font-black px-2 py-1 rounded">PACK</span>
                        </div>
                        <div class="space-y-2 mb-4">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-tighter">Contenu du forfait :</p>
                            @foreach($pack->prestations as $item)
                                <div class="flex justify-between text-sm py-1 border-b border-gray-50 italic text-gray-600">
                                    <span>• {{ $item->name }}</span>
                                    <span>{{ number_format($item->price) }} CFA</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="flex justify-between items-center pt-2">
                            <span class="text-gray-400 text-xs italic">Valeur cumulée : {{ number_format($pack->prestations->sum('price')) }} CFA</span>
                            <span class="text-lg font-black text-indigo-600">{{ number_format($pack->total_price) }} CFA</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Activité par Service</h3>
                <div class="space-y-4">
                    @foreach($serviceStats as $stat)
                        <div class="flex items-center">
                            <div class="flex-1">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">{{ $stat->service ? $stat->service->name : 'Service non défini' }}</span>
                                    <span class="text-sm font-bold text-indigo-600">{{ $stat->count }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ ($stat->count / max($stats['total_prestations'], 1)) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Flux Récents</h3>
                <div class="space-y-3">
                    @foreach($recentPrestations as $recent)
                        <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $recent->name }}</p>
                                <p class="text-xs text-gray-500">{{ $recent->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-sm font-bold {{ $recent->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $recent->is_active ? 'Prêt' : 'Désactivé' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
@endsection