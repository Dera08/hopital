<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Finance') - HospitSIS</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #111827; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #374151; border-radius: 10px; }
        .custom-scrollbar { scrollbar-width: thin; scrollbar-color: #374151 #111827; }
        .animate-fade-in { animation: fadeIn 0.5s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans antialiased h-screen flex flex-col overflow-hidden">

    <!-- Top Header (Simplified Version of App Layout) -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="px-6 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="p-2 rounded-lg hover:bg-gray-100 text-gray-500 transition" title="Retour au Dashboard Admin">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                        <i class="fas fa-wallet text-blue-600"></i>
                        Finance & Trésorerie
                    </h1>
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Espace de Gestion Financière</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-calendar-alt"></i> {{ now()->format('d F Y') }}
                </div>
                <div class="h-8 w-px bg-gray-200"></div>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Horizontal Navigation (The "Sidebar Horizontal") -->
        <div class="px-6 flex overflow-x-auto gap-6 hide-scrollbar border-t border-gray-100">
            @php
                $navItems = [
                    ['route' => 'admin.finance.index', 'label' => "Vue d'ensemble", 'icon' => 'fas fa-chart-pie'],
                    ['route' => 'admin.finance.daily', 'label' => "Recettes Journalières", 'icon' => 'fas fa-cash-register'],
                    ['route' => 'admin.finance.treasury', 'label' => "Trésorerie & Versements", 'icon' => 'fas fa-money-bill-wave'],
                    ['route' => 'admin.finance.pending', 'label' => "Factures & Impayés", 'icon' => 'fas fa-file-invoice-dollar'], 
                    ['route' => 'admin.finance.audit', 'label' => "Audit & Logs", 'icon' => 'fas fa-history'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route']);
                    // Simple logic to keep 'index' active only on the exact index route
                    if($item['route'] == 'admin.finance.index' && (request()->routeIs('admin.finance.daily') || request()->routeIs('admin.finance.treasury') || request()->routeIs('admin.finance.pending') || request()->routeIs('admin.finance.audit'))) $isActive = false;
                @endphp
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center gap-2 py-4 text-xs font-black uppercase tracking-widest border-b-2 transition-all whitespace-nowrap
                   {{ $isActive ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-400 hover:text-gray-600 hover:border-gray-300' }}">
                    <i class="{{ $item['icon'] }}"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-6 overflow-y-auto custom-scrollbar">
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2 animate-fade-in">
                <i class="fas fa-check-circle"></i>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @yield('finance_content')
    </main>

    @stack('scripts')
</body>
</html>

<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
