<x-portal-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Détails de la Consultation') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 to-blue-50/30">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('patient.medical-history') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 transition-all font-medium group">
                    <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à l'historique
                </a>
            </div>

            <!-- Header Card -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-8 mb-6 text-white">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center mb-3">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h1 class="text-3xl font-bold">Consultation Médicale</h1>
                        </div>
                        <p class="text-blue-100 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $record->created_at->format('d/m/Y à H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-white text-blue-700 shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            {{ $record->service->name ?? 'Service Général' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Doctor & Hospital Info -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        @php
                            $isHospitalized = !empty($record->room);
                            $assignedDoctor = $record->doctor ?? $record->room?->doctor ?? null;
                        @endphp
                        
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Médecin Traitant
                            </h3>
                            @if($isHospitalized)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Patient Hospitalisé
                                </span>
                            @endif
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="h-16 w-16 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center text-blue-600 flex-shrink-0 shadow-sm">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-xl text-gray-900">Dr. {{ $assignedDoctor?->name ?? 'Non assigné' }}</p>
                                <p class="text-gray-600 flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    {{ $record->hospital->name ?? 'Hôpital' }}
                                </p>
                                @if($isHospitalized && $record->room)
                                    <p class="text-gray-500 text-sm mt-1">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                        </svg>
                                        Chambre: {{ $record->room->room_number }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Vitals -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            Constantes Vitales
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gradient-to-br from-red-50 to-orange-50 p-4 rounded-xl border border-red-100">
                                <p class="text-xs text-red-600 font-semibold uppercase tracking-wide mb-1">Température</p>
                                <p class="text-2xl font-black text-gray-900">{{ $record->temperature ?? '--' }} <span class="text-sm font-normal">°C</span></p>
                            </div>
                            <div class="bg-gradient-to-br from-blue-50 to-cyan-50 p-4 rounded-xl border border-blue-100">
                                <p class="text-xs text-blue-600 font-semibold uppercase tracking-wide mb-1">Tension</p>
                                <p class="text-2xl font-black text-gray-900">{{ $record->blood_pressure ?? '--' }} <span class="text-sm font-normal">mmHg</span></p>
                            </div>
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-4 rounded-xl border border-green-100">
                                <p class="text-xs text-green-600 font-semibold uppercase tracking-wide mb-1">Poids</p>
                                <p class="text-2xl font-black text-gray-900">{{ $record->weight ?? '--' }} <span class="text-sm font-normal">kg</span></p>
                            </div>
                            <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-4 rounded-xl border border-purple-100">
                                <p class="text-xs text-purple-600 font-semibold uppercase tracking-wide mb-1">Pouls</p>
                                <p class="text-2xl font-black text-gray-900">{{ $record->pulse ?? '--' }} <span class="text-sm font-normal">bpm</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Les informations médicales et le suivi infirmier sont maintenant masqués pour le patient -->
                </div>

                <!-- Right Column -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Lab Tests / Exams (Désactivé car le patient ne doit voir que l'ordonnance) -->
                    @if(false && $record->labRequests && $record->labRequests->count() > 0)
                        {{-- Section masquée --}}
                    @endif

                    <!-- Prescription -->
                    {{-- 1. Ordonnances du nouveau système --}}
                    @if($realPrescriptions->where('category', '!=', 'nurse')->count() > 0)
                    <div class="bg-white rounded-2xl shadow-xl border-l-8 border-pink-500 p-6 overflow-hidden relative">
                        <div class="absolute top-0 right-0 p-2 opacity-10">
                            <i class="fas fa-prescription fa-4x text-pink-600"></i>
                        </div>
                        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                            <span class="bg-pink-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-file-prescription text-pink-600"></i>
                            </span>
                            Ordonnance Médicale
                        </h3>
                        <div class="space-y-5">
                            @foreach($realPrescriptions->where('category', '!=', 'nurse') as $p)
                            <div class="p-4 rounded-xl relative bg-white border border-pink-100 shadow-lg">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-[10px] font-black text-pink-700 bg-pink-100 px-3 py-1 rounded-full uppercase tracking-widest">
                                        💊 PHARMACIE
                                    </span>
                                    <span class="text-[10px] text-gray-400 font-bold">{{ $p->created_at->format('d/m H:i') }}</span>
                                </div>
                                <div class="mb-3">
                                    <p class="font-extrabold text-gray-900 text-lg leading-tight">{{ $p->medication }}</p>
                                    @if($p->dosage || $p->frequency)
                                        <p class="text-sm text-gray-600 mt-1 font-medium">
                                            {{ $p->dosage }} {{ $p->frequency ? '• '.$p->frequency : '' }}
                                        </p>
                                    @endif
                                </div>
                                @if($p->instructions)
                                    <div class="mt-2 bg-white/50 p-3 rounded-lg border border-dashed border-gray-200">
                                        <p class="text-xs text-gray-700 leading-relaxed font-medium">
                                            <i class="fas fa-info-circle mr-1 text-gray-400"></i> {{ $p->instructions }}
                                        </p>
                                    </div>
                                @endif
                                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-between items-center">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 mr-2">
                                            <i class="fas fa-user-md text-sm"></i>
                                        </div>
                                        <p class="text-[10px] font-bold text-gray-500 uppercase">Dr. {{ $p->doctor->name ?? 'Praticien' }}</p>
                                    </div>
                                    @if($p->category !== 'nurse')
                                        <a href="{{ route('patient.prescriptions.download', $p->id) }}" class="flex items-center justify-center w-10 h-10 bg-pink-600 text-white rounded-xl hover:bg-pink-700 transition shadow-md">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Lab Tests / Exams -->
                    @if($record->labRequests && $record->labRequests->count() > 0)
                    <div class="bg-white rounded-2xl shadow-xl border-l-8 border-teal-500 p-6">
                        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                            <span class="bg-teal-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-flask text-teal-600"></i>
                            </span>
                            Résultats d'Analyses
                        </h3>
                        <div class="space-y-6">
                            @foreach($record->labRequests as $labRequest)
                            <div class="border-b border-gray-100 last:border-0 pb-6 last:pb-0">
                                <div class="flex justify-between items-start group">
                                    <div>
                                        <p class="text-[10px] font-bold text-teal-600 uppercase tracking-widest mb-1">{{ $labRequest->test_category }}</p>
                                        <h4 class="text-lg font-black text-gray-800 leading-tight">{{ $labRequest->test_name }}</h4>
                                    </div>
                                    <div class="text-right">
                                        @if($labRequest->status === 'completed')
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black bg-teal-600 text-white shadow-sm ring-4 ring-teal-50">
                                                TERMINÉ
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-black bg-orange-100 text-orange-700">
                                                EN ATTENTE
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($labRequest->status === 'completed')
                                    <div class="mt-4 space-y-3">
                                        @if($labRequest->result)
                                        <div class="bg-teal-50/50 rounded-2xl p-5 border-2 border-teal-100 relative shadow-inner">
                                            <div class="absolute top-0 right-0 p-2 text-teal-200">
                                                <i class="fas fa-quote-right fa-2x"></i>
                                            </div>
                                            <p class="text-[9px] font-black text-teal-600 uppercase mb-2 tracking-tighter">CONCLUSION DU PRATICIEN</p>
                                            <p class="text-teal-900 font-bold text-md leading-relaxed">
                                                {{ $labRequest->result }}
                                            </p>
                                        </div>
                                        @endif
                                        
                                        @if($labRequest->result_data && count($labRequest->result_data) > 0)
                                        <div class="bg-gray-50 rounded-xl p-4 space-y-2 border border-gray-100">
                                            <p class="text-[9px] font-black text-gray-400 uppercase mb-2">VALEURS MESURÉES</p>
                                            @foreach($labRequest->result_data as $key => $val)
                                                <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-0">
                                                    <span class="text-sm text-gray-600 font-medium">{{ $key }}</span>
                                                    <span class="text-sm font-black text-gray-900">{{ $val }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                        @endif

                                        @if($labRequest->result_file)
                                        <a href="{{ asset('storage/' . $labRequest->result_file) }}" target="_blank" 
                                           class="flex items-center justify-center gap-3 w-full py-4 bg-teal-50 text-teal-700 rounded-xl border-2 border-teal-200 font-black text-sm hover:bg-teal-600 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-file-pdf text-xl"></i>
                                            TÉLÉCHARGER LE RAPPORT PDF
                                        </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- 2. Ordonnance rapide (Ancien/Direct) --}}
                    @if(!empty($record->ordonnance))
                    <div class="bg-white rounded-2xl shadow-xl border-l-8 border-purple-500 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-black text-gray-900 flex items-center">
                                <span class="bg-purple-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-notes-medical text-purple-600"></i>
                                </span>
                                Note d'Ordonnance
                            </h3>
                            <a href="{{ route('patient.prescriptions.download', $record->id) }}" class="flex items-center justify-center w-10 h-10 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition shadow-md">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                        <div class="text-gray-900 bg-purple-50/50 p-6 rounded-2xl border-2 border-purple-100 font-mono text-sm leading-relaxed shadow-inner">
                            {!! nl2br(e($record->ordonnance)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- FontAwesome (if not already included) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</x-portal-layout>
