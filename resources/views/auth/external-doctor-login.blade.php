<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Médecin Externe - HospitSIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            {{-- En-tête --}}
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-green-600 rounded-2xl mb-4">
                    <i class="fas fa-user-md text-2xl text-white"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">HospitSIS</h1>
                <p class="text-gray-600 mt-2">Portail Médecin Externe</p>
            </div>

            {{-- Carte du Formulaire --}}
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">Connexion</h2>
                    <p class="text-gray-600 mt-2">Accédez à votre espace professionnel</p>
                </div>

                {{-- Formulaire de connexion --}}
                <form action="{{ route('external.login') }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Champ Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Adresse email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                value="{{ old('email') }}"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                placeholder="votre.email@exemple.com"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Champ Mot de passe --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Mot de passe
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                placeholder="Votre mot de passe"
                            >
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Se souvenir de moi --}}
                    <div class="flex items-center">
                        <input
                            type="checkbox"
                            id="remember"
                            name="remember"
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                        >
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Se souvenir de moi
                        </label>
                    </div>

                    {{-- Bouton de soumission --}}
                    <button
                        type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors"
                    >
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Se connecter
                    </button>
                </form>

                {{-- Liens supplémentaires --}}
                <div class="mt-6 text-center space-y-2">
                    <a href="{{ route('home') }}" class="text-sm text-green-600 hover:text-green-500">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Retour à l'accueil
                    </a>
                </div>
            </div>

            {{-- Pied de page --}}
            <div class="text-center text-sm text-gray-500">
                <p>© 2024 HospitSIS - YA CONSULTING</p>
                <p>Portail de santé sécurisé</p>
            </div>
        </div>
    </div>
</body>
</html>
