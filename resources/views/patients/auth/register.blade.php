<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Patient - Portail</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen py-8 px-4">
    
    <div class="max-w-3xl mx-auto">
        <!-- En-tête -->
        <div class="text-center mb-8">
            <div class="bg-blue-600 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Créer un compte patient</h1>
            <p class="text-gray-600 mt-2">Remplissez le formulaire pour accéder à votre espace</p>
        </div>

        <!-- Carte d'inscription -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('patient.register') }}" class="space-y-6">
                @csrf

                <!-- Informations personnelles -->
                <div class="border-b pb-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informations personnelles</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date de naissance *</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Genre *</label>
                            <select name="gender" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Sélectionner...</option>
                                <option value="Homme" {{ old('gender') == 'Homme' ? 'selected' : '' }}>Homme</option>
                                <option value="Femme" {{ old('gender') == 'Femme' ? 'selected' : '' }}>Femme</option>
                                <option value="Autre" {{ old('gender') == 'Autre' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="border-b pb-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Coordonnées</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone *</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="+225 XX XX XX XX XX">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="email@exemple.com">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Adresse</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ville</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Code postal</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Sécurité -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Sécurité du compte</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mot de passe *</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Minimum 8 caractères">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirmer le mot de passe *</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

                <!-- Boutons -->
                <div class="flex gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-lg">
                        Créer mon compte
                    </button>
                    <a href="{{ route('login') }}"
                        class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg font-semibold hover:bg-gray-300 transition duration-200 text-center">
                        Annuler
                    </a>
                </div>
            </form>

            <!-- Lien connexion -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Vous avez déjà un compte ? 
                    <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-800">
                        Se connecter
                    </a>
                </p>
            </div>

            <!-- Lien retour -->
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    <a href="{{ route('select-portal') }}" class="text-blue-600 font-semibold hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Retour à la sélection de portail
                    </a>
                </p>
            </div>
        </div>
    </div>

</body>
</html>
