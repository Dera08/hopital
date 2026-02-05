<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Espace Santé - {{ $patient->full_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    
    <!-- Header Professionnel -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo et Nom -->
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 w-12 h-12 rounded-xl flex items-center justify-center shadow-md">
                        <span class="text-white font-bold text-xl">{{ substr($patient->first_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-gray-900">{{ $patient->full_name }}</h1>
                        <p class="text-xs text-gray-500">IPU: {{ $patient->ipu }}</p>
                    </div>
                </div>
                
                <!-- Actions Header -->
                <div class="flex items-center space-x-3">
                    <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    <form method="POST" action="{{ route('patient.logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Tabs -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8 overflow-x-auto scrollbar-hide">
                <a href="{{ route('patient.dashboard') }}" class="py-4 px-1 border-b-2 border-blue-600 text-blue-600 font-medium whitespace-nowrap text-sm">
                    <i class="fas fa-home mr-2"></i>Tableau de bord
                </a>
                <a href="{{ route('patient.profile') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap text-sm">
                    <i class="fas fa-user mr-2"></i>Mon profil
                </a>
                <a href="{{ route('patient.appointments') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap text-sm">
                    <i class="fas fa-calendar-alt mr-2"></i>Rendez-vous
                </a>
                <a href="{{ route('patient.prescriptions') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap text-sm">
                    <i class="fas fa-prescription mr-2"></i>Ordonnances
                </a>
                <a href="{{ route('patient.medical-history') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap text-sm">
                    <i class="fas fa-file-medical mr-2"></i>Dossier médical
                </a>
                <a href="{{ route('patient.documents') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap text-sm">
                    <i class="fas fa-folder mr-2"></i>Documents
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenu Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Message de Bienvenue -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Statistiques Rapides -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Carte Âge -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Âge</p>
                        <p class="text-3xl font-bold mt-2">{{ $patient->age }} ans</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-birthday-cake text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Carte Groupe Sanguin -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Groupe sanguin</p>
                        <p class="text-3xl font-bold mt-2">{{ $patient->blood_group ?? 'Non renseigné' }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-tint text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Carte Rendez-vous -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Rendez-vous</p>
                        <p class="text-3xl font-bold mt-2">{{ $totalAppointments }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Carte Ordonnances -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Ordonnances</p>
                        <p class="text-3xl font-bold mt-2">{{ $totalPrescriptions }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                        <i class="fas fa-prescription-bottle text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Principale -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Colonne Principale (2/3) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Prochains Rendez-vous -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-calendar-alt text-blue-600 mr-3"></i>
                            Prochains rendez-vous
                        </h2>
                        <a href="{{ route('patient.book-appointment') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                            <i class="fas fa-plus mr-2"></i>Prendre RDV
                        </a>
                    </div>
                    
                    @if($upcomingAppointments->count() > 0)
                        <div class="space-y-4">
                            @foreach($upcomingAppointments as $appointment)
                                <div class="border-l-4 border-blue-600 bg-blue-50 rounded-r-lg p-4 hover:shadow-md transition">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900">
                                                {{ $appointment->appointment_datetime->format('d/m/Y à H:i') }}
                                            </p>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <i class="fas fa-user-md mr-2"></i>
                                                {{ $appointment->doctor->name ?? 'Médecin non assigné' }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                <i class="fas fa-stethoscope mr-2"></i>
                                                {{ $appointment->service->name ?? 'Service non spécifié' }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                {{ ucfirst($appointment->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('patient.appointments') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Voir tous les rendez-vous <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-600 mb-4">Aucun rendez-vous prévu</p>
                            <a href="{{ route('patient.book-appointment') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Prendre un rendez-vous
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Derniers Dossiers Médicaux -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-file-medical text-green-600 mr-3"></i>
                        Historique médical récent
                    </h2>
                    
                    @if($recentRecords->count() > 0)
                        <div class="space-y-3">
                            @foreach($recentRecords as $record)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-green-500 transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900">{{ $record->created_at->format('d/m/Y') }}</p>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($record->observations ?? $record->reason, 60) }}</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('patient.medical-history') }}" class="mt-4 inline-block text-green-600 hover:text-green-800 font-medium text-sm">
                            Voir l'historique complet <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    @else
                        <p class="text-gray-500 text-center py-8">Aucun dossier médical pour le moment</p>
                    @endif
                </div>

            </div>

            <!-- Colonne Latérale (1/3) -->
            <div class="space-y-6">
                
                <!-- Médecin Référent -->
                @if($patient->referringDoctor)
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-md text-blue-600 mr-2"></i>
                        Médecin référent
                    </h3>
                    <div class="flex items-center space-x-4">
                        <div class="bg-blue-100 w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user-md text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Dr. {{ $patient->referringDoctor->name }}</p>
                            <p class="text-sm text-gray-600">{{ $patient->referringDoctor->role }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Accès Rapides -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Accès rapides</h3>
                    <div class="space-y-3">
                        <a href="{{ route('patient.book-appointment') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition group">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3 group-hover:bg-blue-200 transition">
                                    <i class="fas fa-calendar-plus text-blue-600"></i>
                                </div>
                                <span class="text-gray-700 font-medium">Prendre RDV</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="{{ route('patient.prescriptions') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition group">
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-2 rounded-lg mr-3 group-hover:bg-purple-200 transition">
                                    <i class="fas fa-prescription text-purple-600"></i>
                                </div>
                                <span class="text-gray-700 font-medium">Mes ordonnances</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                        <a href="{{ route('patient.documents') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition group">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-lg mr-3 group-hover:bg-green-200 transition">
                                    <i class="fas fa-folder text-green-600"></i>
                                </div>
                                <span class="text-gray-700 font-medium">Documents</span>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </div>
                </div>

                <!-- Informations Importantes -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-6">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-yellow-600 text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Information</h4>
                            <p class="text-sm text-gray-700">
                                En cas d'urgence, contactez directement le <strong>15</strong> ou présentez-vous aux urgences les plus proches.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </main>

</body>
</html>