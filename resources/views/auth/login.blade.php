{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - HospitSIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    
    <div class="max-w-md w-full">

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

            <form method="POST" action="{{ route('login.process') }}">
                @csrf

                {{-- Email ou IPU --}}
                <div class="mb-4">
                    <label for="identifier" class="block text-sm font-medium text-gray-700 mb-2">
                        Adresse email ou IPU
                    </label>
                    <input type="text" name="identifier" id="identifier" value="{{ old('identifier') }}" required autofocus
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="votre@email.ci ou IPU">
                </div>

                {{-- Mot de passe --}}
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Mot de passe
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                            placeholder="••••••••">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" id="toggle-password">
                            <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>

                {{-- Champ SECRET pour le Super Admin --}}
                <div id="super-admin-field" class="mb-6 hidden">
                    <label for="access_code" class="block text-sm font-medium text-gray-700 mb-2 text-red-600 font-bold">
                        Code d'accès sécurisé
                    </label>
                    <div class="relative">
                        <input type="password" name="access_code" id="access_code" 
                            class="w-full px-4 py-3 border border-red-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition bg-red-50"
                            placeholder="Entrez votre code secret">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-shield-alt text-red-400"></i>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Se souvenir de moi</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800">
                        Mot de passe oublié ?
                    </a>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Se connecter
                </button>

                <div class="mt-6 text-center space-y-2">
                    <p class="text-sm text-gray-600">
                        Nouveau sur HospitSIS ? 
                        <a href="{{ route('patient.register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                            Créer un compte patient
                        </a>
                    </p>
                    <p class="text-xs text-gray-500">
                        Médecin ou personnel ? <a href="{{ route('select-portal') }}" class="hover:text-blue-600 underline">Inscrivez-vous ici</a>
                    </p>
                </div>
            </form>

            {{-- Retour Accueil --}}
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <a href="/" class="inline-flex items-center text-sm text-gray-400 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour à la page d'accueil
                </a>
            </div>
        </div>

        <p class="text-center text-sm text-gray-600 mt-8">
            © 2024 HospitSIS - YA CONSULTING<br>
            <span class="text-xs">Conforme HDS & RGPD</span>
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Gestion de l'affichage du mot de passe
            const toggleButton = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');

            if (toggleButton && passwordInput) {
                toggleButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    const icon = this.querySelector('i');
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            }

            // 2. Détection dynamique de l'email Super Admin
            const identifierInput = document.getElementById('identifier');
            const superAdminField = document.getElementById('super-admin-field');
            const accessCodeInput = document.getElementById('access_code');
            
            // On récupère l'email configuré dans le .env
            const superAdminEmail = "{{ env('SUPER_ADMIN_EMAIL') }}";

            if (identifierInput && superAdminField) {
                identifierInput.addEventListener('input', function() {
                    // Si on tape l'email du super admin, on montre le champ code
                    if (this.value.trim() === superAdminEmail) {
                        superAdminField.classList.remove('hidden');
                        accessCodeInput.required = true;
                    } else {
                        superAdminField.classList.add('hidden');
                        accessCodeInput.required = false;
                    }
                });
            }
        });
    </script>
</body>
</html>