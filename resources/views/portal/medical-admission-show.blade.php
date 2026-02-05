@extends('layouts.portal')
@section('title', 'Détails de l\'Hospitalisation')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Bouton Retour -->
    <a href="{{ route('patient.medical-history') }}" class="inline-flex items-center text-gray-500 hover:text-gray-900 transition-colors mb-6">
        <i class="fas fa-arrow-left mr-2"></i> Retour à l'historique
    </a>

    <!-- Header Admission -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b border-gray-100">
            <div class="flex justify-between items-start">
                <div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700 mb-3">
                        <i class="fas fa-procedures mr-2"></i> Hospitalisation
                    </span>
                    <h1 class="text-2xl font-bold text-gray-900 mb-1">
                        Episode d'hospitalisation
                    </h1>
                    <p class="text-gray-500">Service: <span class="font-semibold text-gray-700">{{ $admission->room->service->name ?? $admission->hospital->name }}</span></p>
                </div>
                <div class="text-right">
                    @if($admission->discharge_date)
                        <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-lg text-sm font-medium">TERMINÉ</span>
                    @else
                        <span class="bg-emerald-100 text-emerald-600 px-3 py-1 rounded-lg text-sm font-medium animate-pulse">EN COURS</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Médecin Traitant -->
            <div class="flex items-start space-x-4">
                <div class="h-10 w-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-md"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Médecin Traitant</h3>
                    <p class="text-sm text-gray-600">{{ $admission->doctor->name }}</p>
                    <p class="text-xs text-blue-500">{{ $admission->doctor->speciality ?? 'Généraliste' }}</p>
                </div>
            </div>

            <!-- Chambre -->
            <div class="flex items-start space-x-4">
                <div class="h-10 w-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-door-open"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Chambre & Lit</h3>
                    <p class="text-sm text-gray-600">Chambre {{ $admission->room->room_number ?? 'N/A' }}</p>
                    <p class="text-xs text-gray-500">Lit {{ $admission->bed->number ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Dates -->
            <div class="flex items-start space-x-4">
                <div class="h-10 w-10 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Période</h3>
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Du:</span> {{ $admission->admission_date->format('d/m/Y H:i') }}
                    </div>
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">Au:</span> {{ $admission->discharge_date ? $admission->discharge_date->format('d/m/Y H:i') : 'Présent' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline des événements -->
    <h2 class="text-lg font-bold text-gray-900 mb-6">Chronologie des soins</h2>
    
    <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">
        
        @forelse($vitals as $vital)
            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                
                <!-- Icone Centrale -->
                <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-slate-50 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 text-slate-500">
                    <i class="fas fa-notes-medical"></i>
                </div>
                
                <!-- Carte Contenu -->
                <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-start mb-2">
                        <time class="font-mono text-xs text-slate-500">{{ $vital->created_at->format('d/m/Y à H:i') }}</time>
                        <span class="text-xs font-bold px-2 py-0.5 rounded bg-blue-50 text-blue-600">
                            {{ $vital->doctor->name ?? 'Infirmier(ère)' }}
                        </span>
                    </div>
                    
                    <h3 class="text-sm font-bold text-gray-900 mb-1">
                        {{ $vital->reason ?? 'Observation Clinique' }}
                    </h3>
                    
                    @if($vital->temperature || $vital->blood_pressure)
                    <div class="flex space-x-3 my-2 text-xs text-gray-600 bg-gray-50 p-2 rounded">
                        @if($vital->temperature) <span><i class="fas fa-temperature-high text-red-400"></i> {{ $vital->temperature }}°C</span> @endif
                        @if($vital->blood_pressure) <span><i class="fas fa-heartbeat text-red-400"></i> {{ $vital->blood_pressure }}</span> @endif
                    </div>
                    @endif

                    <div class="text-sm text-gray-600 prose prose-sm max-w-none">
                       {{ $vital->observations ?? $vital->notes ?? 'Aucune observation détaillée.' }}
                    </div>

                    <div class="mt-3 flex space-x-2">
                        <a href="{{ route('patient.medical-history.show', $vital->id) }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                             Voir dossier complet &rarr;
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-10">
                <p class="text-gray-500">Aucun dossier médical trouvé pour cette admission.</p>
            </div>
        @endforelse
        
    </div>

</div>
@endsection
