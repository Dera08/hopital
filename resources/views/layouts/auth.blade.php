<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion - HospitSIS</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased flex items-center justify-center min-h-screen">
    <div class="w-full max-w-xl mx-auto p-4">
        {{-- Le contenu de la vue de connexion sera inséré ici --}}
        @yield('content')
    </div>
    
    @stack('scripts')
</body>
</html>