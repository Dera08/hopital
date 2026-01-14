<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Patient - HospitSIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8">
        
        <!-- Logo/Icon -->
        <div class="flex justify-center mb-6">
            <div class="bg-blue-600 w-16 h-16 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-center text-gray-800 mb-2">Portail Patient</h1>
        <p class="text-center text-gray-600 mb-8">Connectez-vous à votre espace personnel</p>

        <!-- Messages d'erreur -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- ✅ FORMULAIRE CORRIGÉ - Route patient.login.submit -->
        <form method="POST" action="{{ route('patient.login.submit') }}">
            @csrf

            <!-- IPU ou Email -->
            <div class="mb-4">
                <label for="identifier" class="block text-sm font-medium text-gray-700 mb-2">
                    IPU ou Email
                </label>
                <input 
                    type="text" 
                    name="identifier" 
                    id="identifier" 
                    value="{{ old('identifier') }}"
                    required 
                    autofocus
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('identifier') border-red-500 @enderror"
                    placeholder="Votre IPU ou email"
                >
                @error('identifier')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mot de passe -->
            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Mot de passe
                </label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Se souvenir & Mot de passe oublié -->
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                </label>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-800">
                    Mot de passe oublié ?
                </a>
            </div>

            <!-- Bouton de connexion -->
            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 flex items-center justify-center"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                </svg>
                Se connecter
            </button>
        </form>

        <!-- Lien d'inscription -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Pas encore de compte ? 
                <a href="{{ route('patient.register') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Créer un compte
                </a>
            </p>
        </div>

        <!-- Retour à l'accueil -->
        <div class="mt-4 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-gray-700">
                ← Retour à l'accueil
            </a>
        </div>

    </div>

</body>
</html>