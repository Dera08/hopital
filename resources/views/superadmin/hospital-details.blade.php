<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Hôpital - {{ $hospital->name }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
            color: #1e293b;
        }
        .active-tab {
            color: #2563eb !important;
            border-bottom: 2px solid #2563eb;
            background: linear-gradient(to top, #eff6ff, transparent);
        }
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }
        .stat-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="min-h-screen antialiased">

    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button onclick="window.history.back()" class="p-2 hover:bg-slate-100 rounded-xl transition-colors">
                        <i class="bi bi-arrow-left text-xl"></i>
                    </button>
                    <div class="bg-gradient-to-br from-blue-600 to-blue-700 text-white p-3 rounded-2xl shadow-lg shadow-blue-200/50">
                        <i class="bi bi-building text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $hospital->name }}</h1>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">Détails Complets</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden md:block">
                        <div class="text-[10px] text-slate-400 uppercase font-black tracking-tighter">Statut</div>
                        <div class="font-bold text-slate-900">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $hospital->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $hospital->is_active ? 'ACTIF' : 'INACTIF' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-6">
            <nav class="flex space-x-8">
                <button onclick="showTab('overview')" class="tab-btn active-tab px-4 py-4 text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors border-b-2 border-transparent">
                    <i class="bi bi-house-door mr-2"></i>Aperçu
                </button>
                <button onclick="showTab('staff')" class="tab-btn px-4 py-4 text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors border-b-2 border-transparent">
                    <i class="bi bi-people mr-2"></i>Personnel ({{ $hospital->users->count() }})
                </button>
                <button onclick="showTab('services')" class="tab-btn px-4 py-4 text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors border-b-2 border-transparent">
                    <i class="bi bi-gear mr-2"></i>Services ({{ $hospital->services->count() }})
                </button>
                <button onclick="showTab('prestations')" class="tab-btn px-4 py-4 text-sm font-semibold text-slate-600 hover:text-blue-600 transition-colors border-b-2 border-transparent">
                    <i class="bi bi-cash mr-2"></i>Prestations ({{ $hospital->prestations->count() }})
                </button>
            </nav>
        </div>
    </div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-6 py-8">

        <!-- Overview Tab -->
        <div id="overview" class="tab-pane active">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Stats Cards -->
                <div class="stat-card p-6 rounded-2xl shadow-lg shadow-slate-200/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-600">Personnel Actif</p>
                            <p class="text-3xl font-bold text-slate-900">{{ $hospital->users->where('is_active', true)->count() }}</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-xl">
                            <i class="bi bi-people-fill text-blue-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card p-6 rounded-2xl shadow-lg shadow-slate-200/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-600">Services</p>
                            <p class="text-3xl font-bold text-slate-900">{{ $hospital->services->count() }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-xl">
                            <i class="bi bi-gear-fill text-green-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card p-6 rounded-2xl shadow-lg shadow-slate-200/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-600">Prestations</p>
                            <p class="text-3xl font-bold text-slate-900">{{ $hospital->prestations->count() }}</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-xl">
                            <i class="bi bi-cash-stack text-purple-600 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card p-6 rounded-2xl shadow-lg shadow-slate-200/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-600">Statut</p>
                            <p class="text-lg font-bold {{ $hospital->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $hospital->is_active ? 'ACTIF' : 'INACTIF' }}
                            </p>
                        </div>
                        <div class="bg-{{ $hospital->is_active ? 'green' : 'red' }}-100 p-3 rounded-xl">
                            <i class="bi bi-circle-fill text-{{ $hospital->is_active ? 'green' : 'red' }}-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hospital Information -->
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-8">
                <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                    <i class="bi bi-info-circle text-blue-600 mr-3"></i>
                    Informations Générales
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-2">Nom de l'Hôpital</label>
                            <p class="text-slate-900 font-medium">{{ $hospital->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-2">Adresse</label>
                            <p class="text-slate-900 font-medium">{{ $hospital->address }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-2">Slug</label>
                            <p class="text-slate-900 font-medium font-mono text-sm">{{ $hospital->slug }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-2">Date de Création</label>
                            <p class="text-slate-900 font-medium">{{ $hospital->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-2">Dernière Modification</label>
                            <p class="text-slate-900 font-medium">{{ $hospital->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-600 mb-2">Logo</label>
                            @if($hospital->logo)
                                <img src="{{ asset($hospital->logo) }}" alt="Logo" class="w-16 h-16 object-cover rounded-lg border">
                            @else
                                <p class="text-slate-500 italic">Aucun logo défini</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Tab -->
        <div id="staff" class="tab-pane">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center">
                        <i class="bi bi-people text-blue-600 mr-3"></i>
                        Personnel ({{ $hospital->users->count() }})
                    </h2>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-semibold transition-colors">
                        <i class="bi bi-plus-circle mr-2"></i>Ajouter un membre
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="text-left py-3 px-4 font-semibold text-slate-600">Nom</th>
                                <th class="text-left py-3 px-4 font-semibold text-slate-600">Email</th>
                                <th class="text-left py-3 px-4 font-semibold text-slate-600">Rôle</th>
                                <th class="text-left py-3 px-4 font-semibold text-slate-600">Statut</th>
                                <th class="text-left py-3 px-4 font-semibold text-slate-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hospital->users as $user)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="py-3 px-4">
                                    <div class="flex items-center">
                                        <div class="bg-slate-100 p-2 rounded-lg mr-3">
                                            <i class="bi bi-person text-slate-600"></i>
                                        </div>
                                        <span class="font-medium text-slate-900">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-slate-600">{{ $user->email }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full text-xs font-semibold">
                                        {{ $user->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        <button class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                                            <i class="bi bi-pencil text-slate-600"></i>
                                        </button>
                                        <button class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                                            <i class="bi bi-eye text-slate-600"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-500">
                                    <i class="bi bi-people text-4xl mb-4 block"></i>
                                    Aucun personnel enregistré pour cet hôpital.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Services Tab -->
        <div id="services" class="tab-pane">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center">
                        <i class="bi bi-gear text-blue-600 mr-3"></i>
                        Services ({{ $hospital->services->count() }})
                    </h2>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-semibold transition-colors">
                        <i class="bi bi-plus-circle mr-2"></i>Ajouter un service
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($hospital->services as $service)
                    <div class="border border-slate-200 rounded-xl p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between mb-4">
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <i class="bi bi-gear-fill text-blue-600 text-xl"></i>
                            </div>
                            <span class="px-2 py-1 {{ $service->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full text-xs font-semibold">
                                {{ $service->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </div>
                        <h3 class="font-semibold text-slate-900 mb-2">{{ $service->name }}</h3>
                        <p class="text-sm text-slate-600 mb-3">{{ $service->description }}</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-mono bg-slate-100 px-2 py-1 rounded">{{ $service->code }}</span>
                            <button class="text-blue-600 hover:text-blue-700 font-semibold text-sm">
                                Modifier
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-12 text-center text-slate-500">
                        <i class="bi bi-gear text-4xl mb-4 block"></i>
                        Aucun service enregistré pour cet hôpital.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Prestations Tab -->
        <div id="prestations" class="tab-pane">
            <div class="bg-white rounded-2xl shadow-lg shadow-slate-200/50 p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center">
                        <i class="bi bi-cash text-blue-600 mr-3"></i>
                        Prestations ({{ $hospital->prestations->count() }})
                    </h2>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-semibold transition-colors">
                        <i class="bi bi-plus-circle mr-2"></i>Ajouter une prestation
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="text-left py-3 px-4 font-semibold text-slate-600">Nom</th>
                                <th class="text-left py-3 px-4 font-semibold text-slate-600">Prix</th>
                                <th class="text-left py-3 px-4 font-semibold text-slate-600">Service</th>
                                <th class="text-left py-3 px-4 font-semibold text-slate-600">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($hospital->prestations as $prestation)
                            <tr class="border-b border-slate-100 hover:bg-slate-50">
                                <td class="py-3 px-4">
                                    <div class="flex items-center">
                                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                                            <i class="bi bi-cash text-green-600"></i>
                                        </div>
                                        <span class="font-medium text-slate-900">{{ $prestation->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="font-semibold text-slate-900">{{ number_format($prestation->price, 0, ',', ' ') }} FCFA</span>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        {{ $prestation->service->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    <div class="flex gap-2">
                                        <button class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                                            <i class="bi bi-pencil text-slate-600"></i>
                                        </button>
                                        <button class="p-2 hover:bg-slate-100 rounded-lg transition-colors">
                                            <i class="bi bi-eye text-slate-600"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-500">
                                    <i class="bi bi-cash text-4xl mb-4 block"></i>
                                    Aucune prestation enregistrée pour cet hôpital.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showTab(tabName) {
            // Hide all tab panes
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('active');
            });

            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active-tab');
            });

            // Show selected tab pane
            document.getElementById(tabName).classList.add('active');

            // Add active class to clicked tab button
            event.target.classList.add('active-tab');
        }
    </script>

</body>
</html>
