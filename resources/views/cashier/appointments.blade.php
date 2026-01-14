@extends('layouts.cashier_layout')

@section('content')
<div class="p-4 sm:p-8 bg-gray-50 min-h-screen">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <h2 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tight">Gestion des<br>rendez-vous</h2>
        <button class="bg-blue-600 text-white px-6 py-4 rounded-2xl shadow-xl shadow-blue-200 hover:bg-blue-700 flex items-center gap-3 font-black transition-all w-full md:w-auto justify-center">
            <i class="fas fa-plus"></i> Nouveau RDV
        </button>
    </div>

    {{-- Filtres fonctionnels --}}
    <div class="bg-white p-4 md:p-6 rounded-3xl shadow-sm border border-gray-100 mb-8">
        <div class="relative mb-6">
            <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Rechercher un patient ou un service..." 
                   class="w-full pl-14 pr-6 py-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500 font-medium text-gray-600">
        </div>
        
        <div class="flex flex-wrap gap-2">
            <button onclick="filterStatus('all')" class="status-btn px-6 py-3 bg-blue-600 text-white rounded-xl font-black text-sm transition-all shadow-lg active-filter">Tous</button>
            <button onclick="filterStatus('paid')" class="status-btn px-6 py-3 bg-gray-100 text-gray-500 rounded-xl font-black text-sm hover:bg-gray-200 transition-all">Payés</button>
            <button onclick="filterStatus('pending')" class="status-btn px-6 py-3 bg-gray-100 text-gray-500 rounded-xl font-black text-sm hover:bg-gray-200 transition-all">En attente</button>
        </div>
    </div>

    {{-- Tableau avec Scroll Horizontal si nécessaire --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[1000px]" id="appointmentsTable">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-50">
                        <th class="px-6 py-6 font-bold text-xs uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-6 font-bold text-xs uppercase tracking-wider">Service & Prestations</th>
                        <th class="px-6 py-6 font-bold text-xs uppercase tracking-wider text-center">Heure</th>
                        <th class="px-6 py-6 font-bold text-xs uppercase tracking-wider">Téléphone</th>
                        <th class="px-6 py-6 font-bold text-xs uppercase tracking-wider">Montant Total</th>
                        <th class="px-6 py-6 font-bold text-xs uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-6 font-bold text-xs uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($appointments as $apt)
                    @php 
                        $montantPrestations = $apt->prestations->sum('pivot.total');
                        $totalGeneral = $apt->service->price + $montantPrestations;
                    @endphp
                    <tr class="appointment-row hover:bg-gray-50/50 transition-all" data-status="{{ $apt->status == 'paid' ? 'paid' : 'pending' }}">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center font-black text-xs shrink-0">
                                    {{ strtoupper(substr($apt->patient->name, 0, 2)) }}
                                </div>
                                <span class="font-bold text-gray-800 truncate max-w-[150px]">{{ $apt->patient->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="font-semibold text-gray-700 text-sm">{{ $apt->service->name }}</div>
                            @foreach($apt->prestations as $prestation)
                                <div class="text-[10px] text-blue-500 font-bold uppercase mt-1">
                                    + {{ $prestation->name }} ({{ number_format($prestation->pivot->total, 0, ',', ' ') }} F)
                                </div>
                            @endforeach
                        </td>
                        <td class="px-6 py-5 font-bold text-gray-700 text-center text-sm">
                            {{ \Carbon\Carbon::parse($apt->appointment_datetime)->format('H:i') }}
                        </td>
                        <td class="px-6 py-5 font-medium text-gray-500 text-sm">{{ $apt->patient->phone ?? 'N/A' }}</td>
                        <td class="px-6 py-5 font-black text-gray-800 text-sm">{{ number_format($totalGeneral, 0, ',', ' ') }} F</td>
                        <td class="px-6 py-5">
                            <span class="px-3 py-1.5 rounded-lg {{ $apt->status == 'paid' ? 'bg-green-50 text-green-600' : 'bg-orange-50 text-orange-600' }} text-[10px] font-black italic">
                                {{ $apt->status == 'paid' ? 'Payé' : 'En attente' }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right">
                            @if($apt->status != 'paid')
                                <button onclick="openPaymentModal({{ $apt->id }}, '{{ addslashes($apt->patient->name) }}', {{ $totalGeneral }})"
                                        class="bg-blue-600 text-white px-4 py-2 rounded-xl font-black text-xs hover:shadow-lg transition-all">
                                    Encaisser
                                </button>
                            @else
                                @if($apt->invoice)
                                    <a href="{{ route('cashier.invoices.show', $apt->invoice->id) }}"
                                       class="bg-green-600 text-white px-4 py-2 rounded-xl font-black text-xs hover:shadow-lg transition-all inline-flex items-center gap-2">
                                        <i class="fas fa-eye"></i> Voir Facture
                                    </a>
                                @else
                                    <span class="text-green-500"><i class="fas fa-check-double"></i></span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script pour faire fonctionner les boutons --}}
<script>
function filterTable() {
    let input = document.getElementById("searchInput").value.toUpperCase();
    let rows = document.querySelectorAll(".appointment-row");
    
    rows.forEach(row => {
        let text = row.innerText.toUpperCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}

function filterStatus(status) {
    // UI Update
    document.querySelectorAll('.status-btn').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white', 'shadow-lg');
        btn.classList.add('bg-gray-100', 'text-gray-500');
    });
    event.target.classList.add('bg-blue-600', 'text-white', 'shadow-lg');
    event.target.classList.remove('bg-gray-100', 'text-gray-500');

    // Filtering logic
    let rows = document.querySelectorAll(".appointment-row");
    rows.forEach(row => {
        if (status === 'all') {
            row.style.display = "";
        } else {
            row.style.display = row.getAttribute('data-status') === status ? "" : "none";
        }
    });
}
</script>

@include('cashier.partials.payment_modal')
@endsection