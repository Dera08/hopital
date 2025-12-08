 {{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue - HospitSIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-indigo-50 min-h-screen">
    
    <!-- Header -->
    <header class="absolute w-full z-10">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">HospitSIS</span>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 font-medium">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 font-medium">Connexion</a>
                        <a href="{{ route('portal.login') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">Portail Patient</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                    Système d'Information<br>
                    <span class="text-blue-600">de Santé Moderne</span>
                </h1>
                <p class="text-xl text-gray-600 mb-10 max-w-3xl mx-auto">
                    Une plateforme complète pour la digitalisation de votre établissement de santé. 
                    Conforme HDS et RGPD.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg font-semibold text-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Accès Professionnel
                    </a>
                    <a href="{{ route('portal.login') }}" class="inline-flex items-center justify-center bg-white hover:bg-gray-50 text-gray-900 px-8 py-4 rounded-lg font-semibold text-lg border-2 border-gray-200 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Portail Patient
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="py-20 px-4 bg-white">
        <div class="max-w-7xl mx-auto">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Fonctionnalités Principales</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-8">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Gestion des Patients</h3>
                    <p class="text-gray-600">
                        Dossiers administratifs complets, IPU unique, recherche avancée et historique détaillé.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-8">
                    <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Dossier Patient Informatisé</h3>
                    <p class="text-gray-600">
                        DPI complet avec notes médicales, constantes vitales, prescriptions et documents.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-2xl p-8">
                    <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Gestion des Rendez-vous</h3>
                    <p class="text-gray-600">
                        Agenda intelligent, rappels automatiques, gestion des disponibilités médecins.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-8">
                    <div class="w-12 h-12 bg-orange-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Gestion des Lits</h3>
                    <p class="text-gray-600">
                        Occupation en temps réel, affectation automatique, transferts inter-services.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-2xl p-8">
                    <div class="w-12 h-12 bg-yellow-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Facturation</h3>
                    <p class="text-gray-600">
                        Génération automatique, suivi des paiements, exports PDF pour remboursement.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-gradient-to-br from-red-50 to-pink-50 rounded-2xl p-8">
                    <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Sécurité HDS/RGPD</h3>
                    <p class="text-gray-600">
                        Audit trail complet, chiffrement, MFA, conformité totale aux normes de santé.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-4xl font-bold text-blue-600 mb-2">50%</div>
                    <div class="text-gray-600">Réduction des appels téléphoniques</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-purple-600 mb-2">20%</div>
                    <div class="text-gray-600">Réduction des erreurs de prescription</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-green-600 mb-2">40%</div>
                    <div class="text-gray-600">Temps d'admission réduit</div>
                </div>
                <div>
                    <div class="text-4xl font-bold text-orange-600 mb-2">95%</div>
                    <div class="text-gray-600">Taux d'adoption utilisateur</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12 px-4">
        <div class="max-w-7xl mx-auto text-center">
            <div class="mb-6">
                <div class="inline-flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold">HospitSIS</span>
                </div>
                <p class="text-gray-400">Système d'Information de Santé</p>
            </div>
            <div class="text-sm text-gray-400">
                © 2024 HospitSIS - YA CONSULTING. Tous droits réservés.<br>
                <span class="text-xs">Conforme HDS & RGPD</span>
            </div>
        </div>
    </footer>
</body>
</html>

{{-- resources/views/errors/404.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page non trouvée - HospitSIS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="text-center">
        <div class="mb-8">
            <svg class="mx-auto h-24 w-24 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-6xl font-bold text-gray-900 mb-4">404</h1>
        <p class="text-2xl text-gray-600 mb-8">Page non trouvée</p>
        <p class="text-gray-500 mb-8">La page que vous recherchez n'existe pas ou a été déplacée.</p>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            Retour au Dashboard
        </a>
    </div>
</body>
</html>
