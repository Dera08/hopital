<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portail Patient - HospitSIS')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        [x-cloak] { display: none !important; }

        /* Smooth animations */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Professional card hover effects */
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50" x-data="{ mobileMenuOpen: false }">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100 transition focus:outline-none mr-3">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Portail Patient</h1>
                    <p class="text-sm text-gray-500">HospitSIS</p>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                @if(auth()->guard('patients')->check())
                <span class="text-sm text-gray-600">{{ auth()->guard('patients')->user()->full_name }}</span>
                <form method="POST" action="{{ route('patient.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800">Déconnexion</button>
                </form>
                @endif
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white border-t border-gray-200">
            <nav class="px-4 py-4 space-y-2">
                <a href="{{ route('patient.dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Tableau de bord</a>
                <a href="{{ route('patient.medical-history') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Historique médical</a>
                <a href="{{ route('patient.appointments') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Mes rendez-vous</a>
                <a href="{{ route('patient.documents') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Mes documents</a>
                <a href="{{ route('patient.profile') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">Mon profil</a>
            </nav>
        </div>
    </header>

    <!-- Desktop Navigation -->
    <nav class="hidden md:block bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-8">
                <a href="{{ route('patient.dashboard') }}" class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('patient.dashboard') ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Tableau de bord
                </a>
                <a href="{{ route('patient.medical-history') }}" class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('patient.medical-history*') ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Historique médical
                </a>
                <a href="{{ route('patient.appointments') }}" class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('patient.appointments*') ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Mes rendez-vous
                </a>
                <a href="{{ route('patient.documents') }}" class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('patient.documents*') ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Mes documents
                </a>
                <a href="{{ route('patient.profile') }}" class="py-4 px-1 border-b-2 font-medium text-sm {{ request()->routeIs('patient.profile*') ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Mon profil
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 animate-fade-in">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-auto text-green-700 hover:text-green-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 8000)" class="mb-6 animate-fade-in">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('error') }}</span>
                    <button @click="show = false" class="ml-auto text-red-700 hover:text-red-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">HospitSIS</p>
                        <p class="text-xs text-gray-500">© 2024 - Système d'Information de Santé</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>Version 2.1.0</span>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
