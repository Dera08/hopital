@extends('layouts.admin_finance')

@section('title', 'Factures & Impayés')

@section('finance_content')
<div x-data="{ tab: 'patients' }">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 animate-fade-in">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                <i class="fas fa-file-invoice-dollar text-red-500"></i> Factures & Créances
            </h2>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">
                 Gestion des Recouvrements Patients & Assurances
            </p>
        </div>
        <div class="flex gap-4">
            <div class="bg-red-50 px-6 py-3 rounded-xl shadow-sm border border-red-100 flex items-center gap-4">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-red-400 mb-0.5">Dettes Patients</p>
                    <h3 class="text-xl font-black text-red-600">{{ number_format($totalPatientPending, 0, ',', ' ') }} <small class="text-[10px] opacity-70">FCFA</small></h3>
                </div>
            </div>
            <div class="bg-purple-50 px-6 py-3 rounded-xl shadow-sm border border-purple-100 flex items-center gap-4">
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-purple-400 mb-0.5">Créances Assurances</p>
                    <h3 class="text-xl font-black text-purple-600">{{ number_format($totalInsurancePending, 0, ',', ' ') }} <small class="text-[10px] opacity-70">FCFA</small></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Header -->
    <div class="flex items-center gap-2 mb-6 p-1 bg-gray-100/50 rounded-xl w-fit border border-gray-100">
        <button @click="tab = 'patients'" 
                :class="tab === 'patients' ? 'bg-white shadow-sm text-blue-600 border-gray-200' : 'text-gray-500 hover:text-gray-700 border-transparent'"
                class="px-6 py-2.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all border flex items-center gap-2">
            <i class="fas fa-user"></i> Dettes Patients
        </button>
        <button @click="tab = 'insurance'" 
                :class="tab === 'insurance' ? 'bg-white shadow-sm text-amber-600 border-gray-200' : 'text-gray-500 hover:text-gray-700 border-transparent'"
                class="px-5 py-2.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all border flex items-center gap-2 relative">
            <i class="fas fa-shield-alt"></i> À Recouvrer
            @if(count($insurancePendings) > 0)
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-4 w-4 bg-amber-500 text-[8px] text-white items-center justify-center font-black">{{ count($insurancePendings) }}</span>
                </span>
            @endif
        </button>
        <button @click="tab = 'recovered'" 
                :class="tab === 'recovered' ? 'bg-white shadow-sm text-emerald-600 border-gray-200' : 'text-gray-500 hover:text-gray-700 border-transparent'"
                class="px-5 py-2.5 rounded-lg text-xs font-black uppercase tracking-widest transition-all border flex items-center gap-2">
            <i class="fas fa-check-double"></i> Historique Payés
        </button>
    </div>

    <!-- Patients Tab -->
    <div x-show="tab === 'patients'" class="animate-fade-in">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <h3 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
                    <i class="fas fa-user-clock text-red-400"></i> Factures Patients Non Soldées
                </h3>
                <span class="px-3 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-black uppercase">{{ count($patientPendings) }} dossiers</span>
            </div>
            
            @if(count($patientPendings) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Date / Facture</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Patient</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Service</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Reste à Payer</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($patientPendings as $invoice)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-gray-900">{{ $invoice->created_at->format('d/m/Y') }}</p>
                                    <p class="text-[9px] font-bold text-blue-500">#{{ $invoice->invoice_number }}</p>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $invoice->patient->name ?? 'Inconnu' }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-gray-600">{{ $invoice->service->name ?? 'Général' }}</td>
                                <td class="px-6 py-4 text-right text-sm font-black text-red-600">{{ number_format($invoice->total, 0, ',', ' ') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[9px] font-black rounded-full uppercase">
                                        {{ $invoice->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="p-12 text-center text-gray-400 italic text-sm font-bold">Aucune dette patient.</div>
            @endif
        </div>
    </div>

    <!-- Insurance Tab (Pending) -->
    <div x-show="tab === 'insurance'" class="animate-fade-in" style="display: none;">
        <!-- Insurance Summary Cards -->
        @if(count($statsByInsurance) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            @foreach($statsByInsurance as $name => $stat)
                <div class="bg-white p-4 rounded-xl border border-amber-100 shadow-sm flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-2">
                        <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-black rounded uppercase border border-amber-100">{{ $name ?? 'Inconnu' }}</span>
                        <span class="text-[10px] font-bold text-gray-400">{{ $stat['count'] }} dossiers</span>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Montant Dû</p>
                        <div class="flex items-baseline justify-between">
                            <h4 class="text-lg font-black text-amber-600">{{ number_format($stat['total'], 0, ',', ' ') }} <small class="text-[9px]">F</small></h4>
                            <a href="{{ route('admin.finance.bordereau', ['insurance' => $name]) }}" class="text-[9px] font-black text-amber-500 hover:underline">
                                <i class="fas fa-file-csv mr-1"></i>Exporter
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('admin.finance.bordereau') }}" class="px-5 py-2.5 bg-amber-600 text-white rounded-xl text-xs font-black uppercase tracking-widest flex items-center gap-2 hover:bg-amber-700 transition-all shadow-sm">
                <i class="fas fa-file-export"></i> Générer Bordereau Global
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-amber-50/10">
                <h3 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
                    <i class="fas fa-shield-alt text-amber-400"></i> Créances Assurances à Recouvrer
                </h3>
                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-black uppercase">{{ count($insurancePendings) }} dossiers</span>
            </div>
            
            @if(count($insurancePendings) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Date / Facture</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Assurance</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Patient / Cartes</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Part Assurance</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($insurancePendings as $invoice)
                            @php
                                $insuranceAmount = ($invoice->total * ($invoice->insurance_coverage_rate ?? 0)) / 100;
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-gray-900">{{ $invoice->created_at->format('d/m/Y') }}</p>
                                    <p class="text-[9px] font-bold text-amber-600 font-mono">#{{ $invoice->invoice_number }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 text-[10px] font-black rounded uppercase border border-amber-200">
                                        {{ $invoice->insurance_name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-gray-800">{{ $invoice->patient->name ?? 'Inconnu' }}</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Card ID: {{ $invoice->insurance_card_number ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-black text-amber-600">
                                    {{ number_format($insuranceAmount, 0, ',', ' ') }}
                                    <p class="text-[9px] font-bold text-gray-400 mt-0.5">({{ $invoice->insurance_coverage_rate }}%)</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('admin.finance.settle', $invoice->id) }}" method="POST" onsubmit="return confirm('Confirmer le recouvrement de cette créance ?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-50 text-emerald-600 text-[9px] font-black rounded-lg uppercase border border-emerald-100 hover:bg-emerald-600 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-check-circle mr-1"></i> Valider Règlement
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="p-12 text-center text-gray-400 italic text-sm font-bold">Aucune créance assurance en attente.</div>
            @endif
        </div>
    </div>

    <!-- Recovered Tab (Archives) -->
    <div x-show="tab === 'recovered'" class="animate-fade-in" style="display: none;">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex justify-between items-center bg-emerald-50/10">
                <h3 class="font-black text-gray-800 uppercase tracking-widest text-xs flex items-center gap-2">
                    <i class="fas fa-check-double text-emerald-400"></i> Dossiers Déjà Encaissés
                </h3>
                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase">{{ count($insuranceRecovered) }} archivés</span>
            </div>
            
            @if(count($insuranceRecovered) > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Date Recouv.</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Référence</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">Assurance / Patient</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Montant Recouvré</th>
                            <th class="px-6 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($insuranceRecovered as $invoice)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="text-xs font-bold text-gray-900">{{ $invoice->updated_at->format('d/m/Y') }}</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $invoice->updated_at->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4 text-xs font-black text-blue-600">#{{ $invoice->invoice_number }}</td>
                                <td class="px-6 py-4">
                                    <p class="text-xs font-black text-gray-800">{{ $invoice->patient->name ?? 'Inconnu' }}</p>
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[8px] font-black rounded uppercase">{{ $invoice->insurance_name }}</span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-black text-emerald-600">
                                    {{ number_format(($invoice->total * ($invoice->insurance_coverage_rate ?? 100)) / 100, 0, ',', ' ') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black rounded-lg uppercase border border-emerald-200">
                                        <i class="fas fa-check-circle mr-1"></i> Soldé
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <div class="p-12 text-center text-gray-400 italic text-sm font-bold">Aucun historique de recouvrement.</div>
            @endif
        </div>
    </div>
</div>
@endsection
