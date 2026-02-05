<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Patient - HospitSIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .form-input {
            transition: all 0.3s ease;
        }
        .form-input:focus {
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="bg-[#f8fafc] min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <!-- Decorative Elements -->
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden pointer-events-none -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-100 rounded-full blur-[120px] opacity-50"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-100 rounded-full blur-[120px] opacity-50"></div>
    </div>

    <div class="max-w-2xl w-full space-y-8">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 shadow-lg shadow-blue-200 mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">Créez votre compte patient</h2>
            <p class="mt-3 text-lg text-gray-600">Rejoignez HospitSIS pour gérer vos rendez-vous et votre santé en toute simplicité.</p>
        </div>

        <div class="glass-effect rounded-3xl shadow-2xl p-8 md:p-10">
            @if($errors->any())
                <div class="mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-r-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <ul class="text-sm list-disc list-inside space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('patient.register.submit') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700 ml-1">Nom *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required 
                            class="form-input w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none placeholder-gray-400"
                            placeholder="Votre nom">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700 ml-1">Prénom *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required 
                            class="form-input w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none placeholder-gray-400"
                            placeholder="Votre prénom">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700 ml-1">Date de naissance *</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required 
                            class="form-input w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700 ml-1">Téléphone *</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required 
                            class="form-input w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none placeholder-gray-400"
                            placeholder="+225 ...">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-gray-700 ml-1">Adresse Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                        class="form-input w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none placeholder-gray-400"
                        placeholder="exemple@email.com">
                </div>

                <div class="space-y-1.5">
                    <label class="text-sm font-semibold text-gray-700 ml-1">Votre Hôpital habituel (optionnel)</label>
                    <select name="hospital_id" 
                        class="form-input w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none appearance-none">
                        <option value="" disabled selected>Sélectionnez un hôpital</option>
                        @foreach($hospitals as $hospital)
                            <option value="{{ $hospital->id }}" {{ old('hospital_id') == $hospital->id ? 'selected' : '' }}>
                                {{ $hospital->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700 ml-1">Mot de passe *</label>
                        <input type="password" name="password" required 
                            class="form-input w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none"
                            placeholder="••••••••">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-gray-700 ml-1">Confirmer mot de passe *</label>
                        <input type="password" name="password_confirmation" required 
                            class="form-input w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 outline-none"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-start bg-gray-50 p-4 rounded-xl border border-gray-100">
                    <div class="flex items-center h-5">
                        <input type="checkbox" name="terms" required 
                            class="h-5 w-5 text-blue-600 border-gray-300 rounded-lg focus:ring-blue-500 transition cursor-pointer">
                    </div>
                    <div class="ml-3 text-sm">
                        <label class="font-medium text-gray-700 cursor-pointer">
                            J'accepte les <a href="#" class="text-blue-600 hover:text-blue-700 underline underline-offset-4">conditions d'utilisation</a> et la politique de confidentialité.
                        </label>
                    </div>
                </div>

                <button type="submit" 
                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 rounded-2xl font-bold text-lg hover:shadow-xl hover:shadow-blue-200 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98]">
                    Créer mon compte patient
                </button>

                <p class="text-center text-gray-600">
                    Vous avez déjà un compte ? 
                    <a href="{{ route('login') }}" class="text-blue-600 font-semibold hover:text-blue-700">Se connecter</a>
                </p>
            </form>
        </div>
        
        <p class="text-center text-sm text-gray-500 pb-8">
            &copy; {{ date('Y') }} HospitSIS. Tous droits réservés. Sécurisé par cryptage de bout en bout.
        </p>
    </div>
</body>
</html>