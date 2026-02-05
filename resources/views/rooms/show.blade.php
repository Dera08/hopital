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

    @if(session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
        <p class="text-green-700">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
        <p class="text-red-700">{{ session('error') }}</p>
    </div>
    @endif

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
                    $occupiedCount = $room->beds->where('is_available', false)->count();
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
                        <td class="px-6 py-4 font-bold text-gray-700">{{ $bed->bed_number }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-bold rounded-full {{ $bed->is_available ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                {{ $bed->is_available ? 'Libre' : 'Occupé' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $bed->patient?->name ?? 'Aucun patient' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if($bed->is_available)
                                <button onclick="openAssignModal({{ $bed->id }}, '{{ $bed->bed_number }}')" class="text-blue-600 hover:underline text-sm font-bold">Assigner</button>
                            @else
                                <button onclick="openReleaseModal({{ $bed->id }}, '{{ $bed->bed_number }}')" class="text-red-600 hover:underline text-sm font-bold">Libérer</button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Assignation --}}
<div id="assignModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Assigner un Patient</h3>
        <p class="text-sm text-gray-600 mb-6">Lit : <span id="assignBedName" class="font-bold text-blue-600"></span></p>
        
        <form method="POST" action="{{ route('rooms.assign', $room) }}">
            @csrf
            <input type="hidden" name="bed_id" id="assignBedId">
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Sélectionner le Patient</label>
                <select name="patient_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">-- Choisir un patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }} {{ $patient->first_name }} ({{ $patient->ipu }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-4">
                <button type="button" onclick="closeAssignModal()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Annuler</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Assigner</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Libération --}}
<div id="releaseModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Libérer le Lit</h3>
        <p class="text-sm text-gray-600 mb-6">Êtes-vous sûr de vouloir libérer le lit <span id="releaseBedName" class="font-bold text-red-600"></span> ?</p>
        
        <form method="POST" action="{{ route('rooms.release', $room) }}">
            @csrf
            <input type="hidden" name="bed_id" id="releaseBedId">

            <div class="flex gap-4">
                <button type="button" onclick="closeReleaseModal()" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Annuler</button>
                <button type="submit" class="flex-1 px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">Libérer</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAssignModal(bedId, bedName) {
    document.getElementById('assignBedId').value = bedId;
    document.getElementById('assignBedName').textContent = bedName;
    document.getElementById('assignModal').classList.remove('hidden');
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
}

function openReleaseModal(bedId, bedName) {
    document.getElementById('releaseBedId').value = bedId;
    document.getElementById('releaseBedName').textContent = bedName;
    document.getElementById('releaseModal').classList.remove('hidden');
}

function closeReleaseModal() {
    document.getElementById('releaseModal').classList.add('hidden');
}

// Fermer les modales en cliquant en dehors
document.getElementById('assignModal').addEventListener('click', function(e) {
    if (e.target === this) closeAssignModal();
});

document.getElementById('releaseModal').addEventListener('click', function(e) {
    if (e.target === this) closeReleaseModal();
});
</script>
@endsection