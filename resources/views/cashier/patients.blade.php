@extends('layouts.cashier_layout')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-bold text-gray-800">Liste des Patients</h2>
    <div class="flex gap-2">
        <button class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-filter"></i> <span>Filtrer</span>
        </button>
        <button class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            <i class="fas fa-download"></i> <span>Exporter Excel</span>
        </button>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-blue-500">
        <p class="text-gray-600 text-sm mb-2">Total Patients</p>
        <p class="text-3xl font-bold text-blue-600">{{ $patients->total() }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-green-500">
        <p class="text-gray-600 text-sm mb-2">Patients Actifs</p>
        <p class="text-3xl font-bold text-green-600">{{ $patients->where('is_active', true)->count() }}</p>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md border-l-4 border-purple-500">
        <p class="text-gray-600 text-sm mb-2">Nouveaux ce mois</p>
        <p class="text-3xl font-bold text-purple-600">{{ $patients->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-blue-50 border-b border-blue-100">
            <tr>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Nom & Prénoms</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Téléphone</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Email</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Date d'inscription</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Statut</th>
                <th class="px-6 py-4 text-sm font-semibold text-gray-700">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @foreach($patients as $patient)
            <tr class="hover:bg-gray-50 border-b border-gray-100">
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800">{{ $patient->name }}</div>
                    <div class="text-xs text-gray-400">ID: {{ $patient->id }}</div>
                </td>
                <td class="px-6 py-4 font-medium text-gray-700">{{ $patient->phone ?? 'N/A' }}</td>
                <td class="px-6 py-4 font-medium text-gray-700">{{ $patient->email ?? 'N/A' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $patient->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4">
                    @if($patient->is_active ?? true)
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-black uppercase">
                            Actif
                        </span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[10px] font-black uppercase">
                            Inactif
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex gap-1 justify-end">
                        <button class="bg-blue-50 text-blue-600 p-2 rounded-lg hover:bg-blue-600 hover:text-white transition-all" title="Voir détails">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="bg-green-50 text-green-600 p-2 rounded-lg hover:bg-green-600 hover:text-white transition-all" title="Historique">
                            <i class="fas fa-history"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($patients->hasPages())
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        {{ $patients->links() }}
    </div>
    @endif
</div>
