<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portail Patient - Connexion - HospitSIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    
    <div class="max-w-md w-full">
        <!-- Logo et titre -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-600 rounded-2xl mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900">Portail Patient</h1>
            <p class="text-gray-600 mt-2">HospitSIS - Espace Personnel</p>
        </div>

        <!-- Formulaire de connexion -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-semibold text-gray-900 mb-6">Connexion</h2>

            @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                    </div>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('portal.login') }}">
                @csrf

                <!-- Email ou IPU -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email ou Identifiant Patient (IPU)
                    </label>
                    <input type="text" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        placeholder="votre@email.ci ou PAT2024XXXXX">
                </div>

                <!-- Mot de passe -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Mot de passe
                    </label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                        placeholder="••••••••">
                </div>

                <!-- Se souvenir de moi -->
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                    </label>
                    <a href="#" class="text-sm text-purple-600 hover:text-purple-800">
                        Mot de passe oublié ?
                    </a>
                </div>

                <!-- Bouton de connexion -->
                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Se connecter
                </button>
            </form>

            <!-- Accès professionnel -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-600 mb-3">Vous êtes un professionnel de santé ?</p>
                <a href="{{ route('login') }}" class="block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg transition">
                    Accès Professionnel
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-600 mt-8">
            © 2024 HospitSIS - YA CONSULTING<br>
            <span class="text-xs">Vos données sont sécurisées et protégées</span>
        </p>
    </div>
</body>
</html>