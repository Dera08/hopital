<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HospitSIS | Liste des Hôpitaux</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
            color: #1e293b;
        }
        .pagination { display: flex; list-style: none; gap: 0.5rem; justify-content: center; margin-top: 2rem; }
        .page-item .page-link { 
            padding: 0.75rem 1.25rem;
            border-radius: 1rem;
            background: white;
            border: 2px solid #e2e8f0;
            color: #64748b;
            font-weight: 700;
            transition: all 0.3s;
        }
        .page-item.active .page-link {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.2);
        }
        .page-item.disabled .page-link {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="min-h-screen antialiased p-8">

    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 text-left gap-6">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('superadmin.dashboard') }}" class="text-blue-600 hover:text-blue-700 font-bold text-sm bg-blue-50 px-4 py-2 rounded-xl transition-all">
                                <i class="bi bi-arrow-left mr-2"></i> Retour Dashboard
                            </a>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-5xl font-black text-slate-900 tracking-tighter mb-2">Surveillance des Hôpitaux</h1>
                <p class="text-slate-500 font-medium text-xl">Gestion centralisée des instances SaaS et abonnements.</p>
            </div>
            
            <div class="flex gap-4">
                <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600">
                        <i class="bi bi-hospital text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Hôpitaux</div>
                        <div class="text-2xl font-black text-slate-900">{{ $hospitals->total() }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white/50 backdrop-blur-md p-4 rounded-[2.5rem] border border-white shadow-xl mb-8 flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-6 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" placeholder="Rechercher par nom, ville ou plan..." 
                       class="w-full pl-14 pr-6 py-4 bg-white border-2 border-slate-100 rounded-3xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium">
            </div>
            <select class="px-8 py-4 bg-white border-2 border-slate-100 rounded-3xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-slate-600">
                <option>Tous les plans</option>
                <option>Premium</option>
                <option>Standard</option>
                <option>Basic</option>
            </select>
        </div>

        <!-- Table Holder -->
        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden mb-12">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-400 text-[11px] font-black uppercase tracking-widest border-b border-slate-100">
                            <th class="px-10 py-8 text-left">Identité Hôpital</th>
                            <th class="px-10 py-8 text-left">Plan SaaS</th>
                            <th class="px-10 py-8 text-center">Date Déploiement</th>
                            <th class="px-10 py-8 text-center">Statut</th>
                            <th class="px-10 py-8 text-right">Actions de Contrôle</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($hospitals as $hospital)
                        <tr class="hover:bg-blue-50/40 transition-all duration-300 group">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-5">
                                    <div class="relative">
                                        <div class="w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-[1.5rem] flex items-center justify-center text-slate-400 group-hover:from-blue-100 group-hover:to-blue-200 group-hover:text-blue-600 transition-all shadow-sm">
                                            <i class="bi bi-hospital text-3xl"></i>
                                        </div>
                                        @if($hospital->is_active)
                                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 border-4 border-white rounded-full"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-black text-slate-900 text-xl tracking-tight leading-none mb-2">{{ $hospital->name }}</div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-tighter">
                                                <i class="bi bi-geo-alt-fill"></i> {{ $hospital->address ?? 'Adreese non définie' }}
                                            </span>
                                            <span class="text-[11px] text-slate-300 font-bold uppercase tracking-tighter">ID: #{{ $hospital->id }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <div class="flex flex-col">
                                    <span class="px-4 py-2 bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-widest rounded-xl border border-indigo-100/50 inline-block w-fit">
                                        {{ $hospital->subscriptionPlan->name ?? 'AUCUN PLAN' }}
                                    </span>
                                    @if($hospital->subscriptionPlan)
                                        <div class="text-[10px] text-slate-400 font-bold mt-1">Expire le: {{ now()->addMonths(1)->format('d/m/Y') }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-10 py-8 text-center">
                                <div class="text-sm font-bold text-slate-700">{{ $hospital->created_at->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">{{ $hospital->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-10 py-8 text-center">
                                <label class="relative inline-flex items-center cursor-pointer group/toggle">
                                    <input type="checkbox" 
                                           {{ $hospital->is_active ? 'checked' : '' }} 
                                           onchange="toggleHospitalStatus({{ $hospital->id }}, this.checked)"
                                           class="sr-only peer">
                                    <div class="w-14 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 shadow-inner group-hover/toggle:scale-105 transition-all"></div>
                                    <span class="ml-3 text-[10px] font-black uppercase tracking-widest transition-colors {{ $hospital->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                                        {{ $hospital->is_active ? 'ACTIF' : 'INACTIF' }}
                                    </span>
                                </label>
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex justify-end gap-3">
                                    <button onclick="openHospitalDetails({{ $hospital->id }})" class="w-12 h-12 flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded-2xl transition-all border-2 border-transparent hover:border-blue-100" title="Voir les détails">
                                        <i class="bi bi-eye-fill text-xl"></i>
                                    </button>
                                    <button class="w-12 h-12 flex items-center justify-center text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-2xl transition-all border-2 border-transparent hover:border-purple-100" title="Paramètres">
                                        <i class="bi bi-gear-fill text-xl"></i>
                                    </button>
                                    <div class="relative group/menu">
                                        <button class="w-12 h-12 flex items-center justify-center text-slate-300 hover:text-slate-600 hover:bg-slate-50 rounded-2xl transition-all border-2 border-transparent hover:border-slate-100" title="Plus d'actions">
                                            <i class="bi bi-three-dots-vertical text-xl"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center text-slate-400">
                                <div class="bg-slate-50 border-4 border-dashed border-slate-200 rounded-[4rem] p-20 inline-block">
                                    <i class="bi bi-hospital text-8xl text-slate-200 mb-8 block"></i>
                                    <div class="text-3xl font-black text-slate-900 mb-2">Aucun hôpital trouvé</div>
                                    <p class="text-lg">Votre réseau est actuellement vide.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-10 py-10 bg-slate-50 border-t border-slate-100">
                {!! $hospitals->links() !!}
            </div>
        </div>
    </div>

    @include('superadmin.partials.scripts')
    @include('superadmin.partials.hospital-details-modal')

</body>
</html>
