<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Dossier Médical - Portail Patient</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">

    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('patient.dashboard') }}" class="text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-lg font-bold text-gray-900">Mon Dossier Médical</h1>
                </div>
                <div class="flex items-center">
                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-semibold flex items-center">
                        <span class="h-2 w-2 bg-emerald-500 rounded-full mr-2"></span>
                        Dossier à jour
                    </span>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
                <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-fingerprint text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Identifiant IPU</p>
                    <p class="text-sm font-bold text-gray-900">{{ Auth::guard('patients')->user()->ipu }}</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
                <div class="h-12 w-12 bg-red-50 text-red-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-tint text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Groupe Sanguin</p>
                    <p class="text-sm font-bold text-gray-900">Non renseigné</p>
                </div>
            </div>

            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center space-x-4">
                <div class="h-12 w-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Dernière Visite</p>
                    <p class="text-sm font-bold text-gray-900">
                        {{ $records->count() > 0 ? $records->first()->created_at->format('d/m/Y') : 'Aucune' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-900">Historique des Consultations</h2>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 bg-gray-50 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">
                        <i class="fas fa-download mr-2"></i>Exporter
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Date & Heure</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Médecin / Service</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Observation / Diagnostic</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Admission</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Détails</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $displayedAdmissions = [];
                        @endphp

                        @forelse($records as $record)
                            @php
                                $admission = $record->related_admission;
                                
                                // Si le dossier est lié à une admission
                                if ($admission) {
                                    // LOGIQUE D'AFFICHAGE DU GROUPE :
                                    // 1. Si le dossier est ANTÉRIEUR à l'admission (ex: Consultations pré-admission), on l'affiche SÉPARÉMENT
                                    if ($record->created_at < $admission->admission_date) {
                                        $isGroupRow = false;
                                        $displayDate = $record->created_at;
                                        $displayDoctor = $record->doctor ?? $admission->doctor;
                                        // On ne l'ajoute PAS à displayedAdmissions pour ne pas bloquer l'affichage du vrai groupe plus tard
                                    } 
                                    // 2. Si le dossier fait partie de l'hospitalisation (Date >= Admission)
                                    else {
                                        // Si on a déjà affiché le groupe pour cette admission, on cache ce dossier
                                        if (in_array($admission->id, $displayedAdmissions)) {
                                            continue;
                                        }
                                        
                                        // Sinon, on affiche le GROUPE "Episode Hospitalisation"
                                        $displayedAdmissions[] = $admission->id;
                                        $displayDate = $admission->admission_date;
                                        $displayDoctor = $admission->doctor;
                                        $isGroupRow = true;
                                    }
                                } else {
                                    // Dossier standard (Consultation simple)
                                    $displayDate = $record->created_at;
                                    $displayDoctor = $record->doctor;
                                    $isGroupRow = false;
                                }
                            @endphp

                            <tr class="hover:bg-blue-50/20 transition-colors {{ $isGroupRow ? 'bg-orange-50/30' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $displayDate->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $displayDate->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <div>
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ $displayDoctor?->name ?? 'Médecin non assigné' }}
                                            </div>
                                            
                                            <!-- Badge Hospitalisation -->
                                            @if($isGroupRow)
                                                <div class="mt-2 flex flex-col items-start space-y-1">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-orange-100 text-orange-700">
                                                        <i class="fas fa-procedures mr-2"></i> Episode Hospitalisation
                                                    </span>
                                                    <div class="text-[10px] text-gray-500 bg-white px-2 py-1 rounded border border-gray-200 shadow-sm">
                                                        <div><span class="font-semibold">Arrivée:</span> {{ $admission->admission_date->format('d/m/Y H:i') }}</div>
                                                        @if($admission->discharge_date)
                                                            <div><span class="font-semibold">Sortie:</span> {{ $admission->discharge_date->format('d/m/Y H:i') }}</div>
                                                        @else
                                                            <div class="text-emerald-600 font-medium">En cours</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-xs text-blue-600 font-medium">{{ $record->service->name ?? 'Service Général' }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($isGroupRow)
                                        <p class="text-sm text-gray-600 italic">
                                            <i class="fas fa-layer-group text-gray-400 mr-1"></i>
                                            Voir le détail complet de l'épisode de soins (Hospitalisation)
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-600 italic">
                                            {{ Str::limit($record->observations ?? $record->reason, 60) }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($admission)
                                        <div class="flex flex-col">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-orange-100 text-orange-700 w-max mb-1">
                                                <i class="fas fa-procedures mr-1"></i> Admis
                                            </span>
                                            <div class="text-[10px] text-gray-500">
                                                <div class="flex items-center"><i class="fas fa-sign-in-alt mr-1 opacity-50"></i> {{ $admission->admission_date->format('d/m/Y H:i') }}</div>
                                                @if($admission->discharge_date)
                                                    <div class="flex items-center text-gray-400"><i class="fas fa-sign-out-alt mr-1 opacity-50"></i> {{ $admission->discharge_date->format('d/m/Y H:i') }}</div>
                                                @else
                                                    <div class="flex items-center text-emerald-600 font-bold"><i class="fas fa-clock mr-1 opacity-50"></i> En cours</div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-500 w-max">
                                            <i class="fas fa-minus mr-1 opacity-30"></i> Non admis
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($isGroupRow)
                                        <a href="{{ route('patient.medical-history.admission.show', $admission->id) }}" class="inline-flex items-center px-3 py-1 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition-colors shadow-sm">
                                            Détails complets <i class="fas fa-chevron-right ml-2 text-xs"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('patient.medical-history.show', $record->id) }}" class="inline-flex items-center justify-center h-8 w-8 bg-gray-50 rounded-full text-gray-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                            <i class="fas fa-chevron-right text-xs"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="h-20 w-20 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                            <i class="fas fa-notes-medical text-4xl"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Historique vide</h3>
                                        <p class="text-gray-500 max-w-xs mx-auto text-sm">
                                            Votre historique médical s'affichera ici après vos consultations avec nos médecins.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($records->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $records->links() }}
                </div>
            @endif
        </div>

        <div class="mt-8 p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-shield-alt text-emerald-600"></i>
                <p class="text-sm text-emerald-800 font-medium">
                    Vos données de santé sont cryptées et protégées conformément à la réglementation en vigueur.
                </p>
            </div>
            <i class="fas fa-lock text-emerald-200 text-xl"></i>
        </div>
    </main>

</body>
</html>