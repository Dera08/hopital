<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HospitSIS') - Système d'Information de Santé</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }

        /* CORRECTION DES DEUX TRAITS (Scrollbar) */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #111827;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #374151;
            border-radius: 10px;
        }
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #374151 #111827;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="min-h-screen bg-gray-50">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
