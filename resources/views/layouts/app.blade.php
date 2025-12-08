 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HospitSIS') - Système d'Information de Santé</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js pour l'interactivité -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Styles personnalisés -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">
    
    <div x-data="{ sidebarOpen: true, mobileMenuOpen: false }" class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Desktop -->
         <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="flex flex-col bg-gray-900 text-white transition-all duration-300">
            
            <!-- Logo -->
            <div class="flex items-center justify-between px-4 py-6 border-b border-gray-800">
                <div x-show="sidebarOpen" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">HospitSIS</h1>
                        <p class="text-xs text-gray-400">v1.0</p>
                    </div>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-6 space-y-2 overflow-y-auto">
                
                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('dashboard') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium">Tableau de Bord</span>
                </a>

                <!-- Patients -->
                <a href="{{ route('patients.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('patients.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium">Patients</span>
                </a>

                <!-- Rendez-vous -->
                <a href="{{ route('appointments.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('appointments.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium">Rendez-vous</span>
                </a>

                <!-- Admissions -->
                <a href="{{ route('admissions.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('admissions.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium">Admissions</span>
                </a>

                @can('viewAny', App\Models\Prescription::class)
                <!-- Prescriptions -->
                <a href="{{ route('prescriptions.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('prescriptions.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium">Prescriptions</span>
                </a>
                @endcan

                <!-- Gestion des lits -->
                <a href="{{ route('rooms.bed-management') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('rooms.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium">Gestion des Lits</span>
                </a>

                @if(auth()->user()->isAdministrative() || auth()->user()->isAdmin())
                <div x-show="sidebarOpen" class="pt-6 pb-2">
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Administration</p>
                </div>

                <!-- Facturation -->
                <a href="{{ route('invoices.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('invoices.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium">Facturation</span>
                </a>

                <!-- Utilisateurs -->
                <a href="{{ route('users.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('users.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium">Utilisateurs</span>
                </a>

                <!-- Rapports -->
                <a href="{{ route('reports.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('reports.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium">Rapports</span>
                </a>
                @endif

                @if(auth()->user()->isAdmin())
                <!-- Audit Logs -->
                <a href="{{ route('audit-logs.index') }}" class="flex items-center space-x-3 px-3 py-2.5 rounded-lg hover:bg-gray-800 transition {{ request()->routeIs('audit-logs.*') ? 'bg-gray-800 border-l-4 border-blue-500' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    <span x-show="sidebarOpen" class="font-medium">Logs d'Audit</span>
                </a>
                @endif
            </nav>

            <!-- User Profile -->
            <div class="border-t border-gray-800 p-4">
                <div x-show="sidebarOpen" class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center">
                            <span class="text-sm font-bold">{{ substr(Auth::user()->name, 0, 2) }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400 capitalize">{{ Auth::user()->role }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 hover:bg-gray-800 rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Bar Mobile -->
            <header class="lg:hidden bg-white shadow-sm px-4 py-3 flex items-center justify-between">
           <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg hover:bg-gray-100">
        </button>
      <h1 class="text-lg font-bold text-gray-900">HospitSIS</h1>
      <div class="w-10"></div>
     </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto">
                
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 m-4">
                    <div class="flex">
                        <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 p-4 m-4">
                    <div class="flex">
                        <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="ml-3">
                            @foreach($errors->all() as $error)
                            <p class="text-sm text-red-700">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    
    <script>
        // Configuration CSRF pour les requêtes AJAX
        document.addEventListener('DOMContentLoaded', function() {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            
            window.axios = axios;
            window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
        });
    </script>
</body>
</html>