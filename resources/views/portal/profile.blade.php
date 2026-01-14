<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - {{ $patient->full_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('patient.dashboard') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h1 class="text-lg font-bold text-gray-900">Mon Profil</h1>
                </div>
                <form method="POST" action="{{ route('patient.logout') }}">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition">
                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- Navigation Tabs -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8 overflow-x-auto scrollbar-hide">
                <a href="{{ route('patient.dashboard') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap text-sm">
                    <i class="fas fa-home mr-2"></i>Tableau de bord
                </a>
                <a href="{{ route('patient.profile') }}" class="py-4 px-1 border-b-2 border-blue-600 text-blue-600 font-medium whitespace-nowrap text-sm">
                    <i class="fas fa-user mr-2"></i>Mon profil
                </a>
                <a href="{{ route('patient.appointments') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap text-sm">
                    <i class="fas fa-calendar-alt mr-2"></i>Rendez-vous
                </a>
                <a href="{{ route('patient.prescriptions') }}" class="py-4 px-1 border-b-2 border-transparent text-gray-600 hover:text-gray-900 whitespace-nowrap text-sm">
                    <i class="fas fa-prescription mr-2"></i>Ordonnances
                </a>
            </div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg flex items-center">
                <i class="fas fa-check-circle text-green-500 mr-3 text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3 text-xl mt-1"></i>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Colonne Latérale - Carte Identité -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 text-center">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-700 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <span class="text-white font-bold text-4xl">{{ substr($patient->first_name, 0, 1) }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 mb-1">{{ $patient->full_name }}</h2>
                    <p class="text-sm text-gray-500 mb-4">IPU: {{ $patient->ipu }}</p>
                    
                    <div class="space-y-3 text-left mt-6">
                        <div class="flex items-center text-sm">
                            <i class="fas fa-birthday-cake text-blue-600 w-5 mr-3"></i>
                            <span class="text-gray-700">{{ $patient->age }} ans</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-venus-mars text-blue-600 w-5 mr-3"></i>
                            <span class="text-gray-700">{{ $patient->gender === 'M' ? 'Masculin' : ($patient->gender === 'F' ? 'Féminin' : 'Autre') }}</span>
                        </div>
                        @if($patient->blood_group)
                        <div class="flex items-center text-sm">
                            <i class="fas fa-tint text-red-600 w-5 mr-3"></i>
                            <span class="text-gray-700">Groupe {{ $patient->blood_group }}</span>
                        </div>
                        @endif
                        <div class="flex items-center text-sm">
                            <i class="fas fa-calendar text-blue-600 w-5 mr-3"></i>
                            <span class="text-gray-700">Membre depuis {{ $patient->created_at->format('Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Carte Sécurité -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-xl p-5 mt-6">
                    <div class="flex items-start">
                        <i class="fas fa-shield-alt text-yellow-600 text-xl mr-3 mt-1"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Sécurité</h4>
                            <p class="text-xs text-gray-700">
                                Vos données sont protégées et conformes aux normes de confidentialité médicale.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne Principale - Formulaire -->
            <div class="lg:col-span-2">
                <form method="POST" action="{{ route('patient.profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Section Informations Personnelles -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-user-circle text-blue-600 mr-3"></i>
                            Informations personnelles
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nom</label>
                                <input 
                                    type="text" 
                                    value="{{ $patient->name }}" 
                                    disabled
                                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed"
                                >
                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fas fa-lock mr-1"></i>Non modifiable
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Prénom</label>
                                <input 
                                    type="text" 
                                    value="{{ $patient->first_name }}" 
                                    disabled
                                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed"
                                >
                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fas fa-lock mr-1"></i>Non modifiable
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Date de naissance</label>
                                <input 
                                    type="text" 
                                    value="{{ $patient->dob->format('d/m/Y') }}" 
                                    disabled
                                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">IPU</label>
                                <input 
                                    type="text" 
                                    value="{{ $patient->ipu }}" 
                                    disabled
                                    class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Section Contact -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-address-book text-green-600 mr-3"></i>
                            Informations de contact
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-2"></i>Email *
                                </label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email', $patient->email) }}" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-2"></i>Téléphone *
                                </label>
                                <input 
                                    type="tel" 
                                    name="phone" 
                                    value="{{ old('phone', $patient->phone) }}" 
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt mr-2"></i>Adresse
                                </label>
                                <textarea 
                                    name="address" 
                                    rows="2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >{{ old('address', $patient->address) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                                    <input 
                                        type="text" 
                                        name="city" 
                                        value="{{ old('city', $patient->city) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Code postal</label>
                                    <input 
                                        type="text" 
                                        name="postal_code" 
                                        value="{{ old('postal_code', $patient->postal_code) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section Contacts d'Urgence -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-ambulance text-red-600 mr-3"></i>
                            Contact d'urgence
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-2"></i>Nom du contact
                                </label>
                                <input 
                                    type="text" 
                                    name="emergency_contact_name" 
                                    value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Nom de la personne à contacter"
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone-alt mr-2"></i>Téléphone d'urgence
                                </label>
                                <input 
                                    type="tel" 
                                    name="emergency_contact_phone" 
                                    value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="+225 XX XX XX XX XX"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Section Sécurité - Changer le mot de passe -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-key text-yellow-600 mr-3"></i>
                            Changer le mot de passe
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-2"></i>Nouveau mot de passe
                                </label>
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Laisser vide pour ne pas changer"
                                >
                                <p class="mt-1 text-xs text-gray-500">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Minimum 8 caractères
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-2"></i>Confirmer le mot de passe
                                </label>
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Retapez le mot de passe"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de Soumission -->
                    <div class="flex items-center justify-end space-x-4">
                        <a 
                            href="{{ route('patient.dashboard') }}" 
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition"
                        >
                            <i class="fas fa-times mr-2"></i>Annuler
                        </a>
                        <button 
                            type="submit" 
                            class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold shadow-md"
                        >
                            <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                        </button>
                    </div>

                </form>
            </div>

        </div>

    </main>

</body>
</html>