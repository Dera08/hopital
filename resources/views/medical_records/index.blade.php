@extends('layouts.app')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto">
        {{-- En-tête dynamique --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    {{ isset($is_archive) && $is_archive ? '📦 Archives Médicales' : '🩺 Dossiers Médicaux Reçus' }}
                </h1>
                <p class="text-sm text-gray-500 font-medium mt-1">
                    {{ isset($is_archive) && $is_archive ? 'Consultation des dossiers historiques clôturés.' : 'Flux en temps réel des constantes transmises par l\'infirmerie.' }}
                </p>
            </div>
            
            {{-- Badge de résumé rapide --}}
            <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ isset($is_archive) && $is_archive ? 'bg-gray-400' : 'bg-green-400' }} opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 {{ isset($is_archive) && $is_archive ? 'bg-gray-500' : 'bg-green-500' }}"></span>
                </span>
                <span class="text-sm font-bold text-gray-700">{{ $records->count() }} Patient(s)</span>
            </div>
        </div>

        {{-- Conteneur du Tableau --}}
        <div class="bg-white shadow-xl shadow-gray-200/50 rounded-2xl border border-gray-100 overflow-hidden overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Identité Patient</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Prestation</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Motif</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Priorité</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest whitespace-nowrap">Signes Vitaux</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($records as $record)
                        <tr class="hover:bg-blue-50/40 transition-all duration-200 group">
                            {{-- Cellule Patient avec Initiale --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-lg shadow-inner shadow-white/20 flex-shrink-0">
                                        {{ strtoupper(substr($record->patient_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-extrabold text-gray-900 group-hover:text-blue-700 transition-colors whitespace-nowrap">{{ $record->patient_name }}</div>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="text-[10px] font-bold text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded uppercase tracking-tighter">IPU</span>
                                            <span class="text-xs font-mono text-gray-500">{{ $record->patient_ipu }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Cellule Prestation --}}
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $record->prestation }}
                                </span>
                            </td>

                            {{-- Cellule Motif --}}
                            <td class="px-6 py-5">
                                <p class="text-sm text-gray-700 font-medium line-clamp-2 max-w-[200px]" title="{{ $record->reason }}">
                                    {{ $record->reason ?? 'Aucun motif' }}
                                </p>
                            </td>

                            {{-- Cellule Médecin Assigné --}}
                            <td class="px-6 py-5">
                                @if($record->doctor)
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 border border-blue-100 shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold text-gray-800">Dr. {{ $record->doctor->name }}</div>
                                            <div class="text-[9px] font-black text-blue-600 uppercase tracking-tighter">{{ $record->doctor->service->name ?? 'Service Médical' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black bg-gray-100 text-gray-500 uppercase tracking-widest border border-gray-200">
                                        Attente Assignation
                                    </span>
                                @endif
                            </td>

                            {{-- Cellule Urgence avec Badge --}}
                            <td class="px-6 py-5">
                                @if($record->urgency === 'critique')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-700 text-xs font-black rounded-lg border border-red-100 ring-4 ring-red-50/50">
                                        <span class="h-1.5 w-1.5 rounded-full bg-red-600 animate-pulse"></span>
                                        {{ strtoupper($record->urgency) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 text-xs font-black rounded-lg border border-amber-100">
                                        {{ strtoupper($record->urgency) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Cellule Constantes --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">Température</span>
                                        <span class="text-sm font-bold {{ (float)$record->temperature >= 38 ? 'text-orange-600' : 'text-gray-700' }}">
                                            {{ $record->temperature }}°C
                                        </span>
                                    </div>
                                    <div class="h-8 w-px bg-gray-100"></div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">Tension</span>
                                        <span class="text-sm font-bold text-gray-700">{{ $record->blood_pressure }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Cellule Action --}}
                            <td class="px-6 py-5 text-right">
                                <div class="flex items-center gap-2 justify-end">
                                    @if(isset($is_archive) && $is_archive)
                                        {{-- Mode Archive : Lien vers la vue archive lecture seule --}}
                                        @php
                                            $patient = \App\Models\Patient::where('ipu', $record->patient_ipu)->first();
                                        @endphp
                                        @if($patient)
                                            <a href="{{ route('patients.archives.show', $patient->id) }}"
                                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-gray-600 text-gray-600 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-gray-600 hover:text-white transition-all transform active:scale-95 shadow-sm">
                                                Consulter
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">Patient introuvable</span>
                                        @endif
                                    @else
                                        {{-- Mode Normal : Lien vers la vue normale --}}
                                        <a href="{{ route('medical-records.show', $record->id) }}"
                                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-blue-600 text-blue-600 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-blue-600 hover:text-white transition-all transform active:scale-95 shadow-sm">
                                            Consulter
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                                        </a>
                                    @endif

                                    @if(auth()->user()?->role === 'doctor' || auth()->user()?->role === 'admin' || auth()->user()?->role === 'internal_doctor')
                                    <form action="{{ route('medical-records.destroy', $record->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce dossier médical ? Cette action est irréversible.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border-2 border-red-600 text-red-600 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-red-600 hover:text-white transition-all transform active:scale-95 shadow-sm">
                                            Suppression
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-24 text-center">
                                <div class="flex flex-col items-center justify-center opacity-30">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <p class="text-xl font-black text-gray-900">Aucun dossier disponible</p>
                                    <p class="text-sm font-medium">Tout est à jour pour le moment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection