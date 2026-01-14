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
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Détails</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($records as $record)
                            <tr class="hover:bg-blue-50/20 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-gray-900">{{ $record->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-400">{{ $record->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $record->doctor->name ?? 'Médecin' }}</div>
                                    <div class="text-xs text-blue-600 font-medium">{{ $record->service->name ?? 'Service Général' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600 italic">
                                        {{ Str::limit($record->observation ?? $record->diagnosis, 60) }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="h-8 w-8 bg-gray-50 rounded-full text-gray-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    </button>
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