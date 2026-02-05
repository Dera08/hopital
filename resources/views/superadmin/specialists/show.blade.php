<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HospitSIS | Profil Spécialiste - {{ $specialist->nom }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: linear-gradient(135deg, #f8fafc 0%, #eff6ff 100%);
            color: #1e293b;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .tab-btn.active {
            background: white;
            color: #2563eb;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.1);
        }
    </style>
</head>
<body class="min-h-screen antialiased p-8">

    <div class="max-w-7xl mx-auto">
        <!-- Header & Navigation -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-12 gap-6">
            <div>
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('superadmin.specialists.index') }}" class="text-blue-600 hover:text-blue-700 font-bold text-sm bg-blue-50 px-4 py-2 rounded-xl transition-all">
                                <i class="bi bi-arrow-left mr-2"></i> Retour à la liste
                            </a>
                        </li>
                    </ol>
                </nav>
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2rem] flex items-center justify-center text-white shadow-xl shadow-blue-200">
                        <i class="bi bi-person-fill text-5xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-black text-slate-900 tracking-tighter">Dr. {{ $specialist->prenom }} {{ $specialist->nom }}</h1>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-widest">{{ $specialist->specialite }}</span>
                            <span class="text-slate-400 font-bold text-sm">ID Specialist: #{{ $specialist->id }}</span>
                            <span class="px-3 py-1 {{ $specialist->statut === 'actif' ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }} rounded-lg text-[10px] font-black uppercase tracking-widest">
                                {{ $specialist->statut }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-4">
                <button onclick="window.print()" class="px-6 py-3 bg-white border border-slate-200 rounded-2xl font-bold text-slate-600 hover:bg-slate-50 transition-all flex items-center gap-2">
                    <i class="bi bi-printer"></i> Imprimer Rapport
                </button>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="bi bi-currency-exchange text-2xl"></i>
                </div>
                <div class="text-3xl font-black text-slate-900 tracking-tighter">{{ number_format($stats['total_earned']) }} <span class="text-xs">FCFA</span></div>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Commissions Système</div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="bi bi-wallet2 text-2xl"></i>
                </div>
                <div class="text-3xl font-black text-slate-900 tracking-tighter">{{ number_format($stats['specialist_balance']) }} <span class="text-xs">FCFA</span></div>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Solde Praticien</div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="bi bi-calendar-check text-2xl"></i>
                </div>
                <div class="text-3xl font-black text-slate-900 tracking-tighter">{{ $stats['consultations_count'] }}</div>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Actes Réalisés</div>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-6">
                    <i class="bi bi-star-fill text-2xl"></i>
                </div>
                <div class="text-3xl font-black text-slate-900 tracking-tighter">4.8 <span class="text-xs text-slate-400">/5</span></div>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Note Moyenne</div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-slate-200/50 p-2 rounded-[2rem] flex gap-2 mb-8 max-w-2xl mx-auto overflow-x-auto">
            <button onclick="switchTab('apercu')" id="btn-apercu" class="tab-btn active flex-1 px-6 py-4 rounded-[1.5rem] font-black text-xs uppercase tracking-widest transition-all whitespace-nowrap">Dashboard</button>
            <button onclick="switchTab('rdv')" id="btn-rdv" class="tab-btn flex-1 px-6 py-4 rounded-[1.5rem] font-black text-xs uppercase tracking-widest transition-all whitespace-nowrap">RDV & Patients</button>
            <button onclick="switchTab('prestations')" id="btn-prestations" class="tab-btn flex-1 px-6 py-4 rounded-[1.5rem] font-black text-xs uppercase tracking-widest transition-all whitespace-nowrap">Catalogue</button>
            <button onclick="switchTab('avis')" id="btn-avis" class="tab-btn flex-1 px-6 py-4 rounded-[1.5rem] font-black text-xs uppercase tracking-widest transition-all whitespace-nowrap">Avis Patients</button>
        </div>

        <!-- Tab Contents -->
        <div class="tab-content" id="tab-apercu">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8">
                    <!-- Transactions Chart Mock -->
                    <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-xl overflow-hidden relative">
                        <div class="flex items-center justify-between mb-10">
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 tracking-tighter">Courbe de Performance</h3>
                                <p class="text-slate-500 font-medium">Revenus et commissions sur les 30 derniers jours.</p>
                            </div>
                            <select class="bg-slate-50 border-none rounded-xl px-4 py-2 font-bold text-sm">
                                <option>30 derniers jours</option>
                            </select>
                        </div>
                        <div class="h-64 w-full bg-slate-50 rounded-3xl flex items-end justify-between p-8 gap-2">
                            @foreach([40, 60, 45, 90, 65, 80, 100, 70, 85, 95, 110, 100] as $h)
                                <div class="w-full bg-blue-600 rounded-t-xl opacity-20 hover:opacity-100 transition-all cursor-pointer" style="height: {{ $h }}%"></div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl overflow-hidden">
                        <div class="p-10 border-b border-slate-50 flex items-center justify-between">
                            <h3 class="text-2xl font-black text-slate-900 tracking-tighter">Flux Financier Récent</h3>
                            <button class="text-blue-600 font-black text-xs uppercase tracking-widest">Voir Tout</button>
                        </div>
                        <div class="p-4">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                        <th class="px-6 py-4">Date</th>
                                        <th class="px-6 py-4">Description</th>
                                        <th class="px-6 py-4">Montant</th>
                                        <th class="px-6 py-4">Com.</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($transactions->take(5) as $tx)
                                    <tr class="hover:bg-slate-50 transition-all">
                                        <td class="px-6 py-6 text-sm font-bold text-slate-500">{{ $tx->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-6">
                                            <div class="font-bold text-slate-900">{{ $tx->description }}</div>
                                        </td>
                                        <td class="px-6 py-6 font-black text-slate-900">{{ number_format($tx->amount) }} <span class="text-[10px]">FCFA</span></td>
                                        <td class="px-6 py-6 font-black text-emerald-600">{{ number_format($tx->fee_applied) }} <span class="text-[10px]">FCFA</span></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="py-10 text-center text-slate-400">Aucune transaction répertoriée</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <!-- Wallet Card -->
                    <div class="bg-slate-900 rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-slate-400">
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-12">
                                <div class="w-16 h-12 bg-white/10 rounded-xl flex items-center justify-center border border-white/20 backdrop-blur-md">
                                    <i class="bi bi-wallet2 text-2xl"></i>
                                </div>
                                <div class="text-right">
                                    <div class="text-[10px] font-black uppercase tracking-widest opacity-60">Statut Compte</div>
                                    <div class="text-emerald-400 font-black flex items-center gap-1 justify-end">
                                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div> OK
                                    </div>
                                </div>
                            </div>
                            <div class="mb-8">
                                <div class="text-xs font-bold uppercase tracking-widest opacity-50 mb-2">Solde Actuel</div>
                                <div class="text-5xl font-black tracking-tighter">{{ number_format($stats['specialist_balance']) }} <span class="text-lg">FCFA</span></div>
                            </div>
                            <div class="pt-8 border-t border-white/10">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="opacity-50 font-bold uppercase">Membre depuis</span>
                                    <span class="font-black">{{ $specialist->created_at->format('M Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <i class="bi bi-shield-check absolute -right-10 -bottom-10 text-[15rem] opacity-5 rotate-12"></i>
                    </div>

                    <!-- Action Quick Links -->
                    <div class="bg-white p-8 rounded-[3rem] border border-slate-100 shadow-xl space-y-4">
                        <h4 class="font-black text-slate-900 uppercase tracking-widest text-[10px] mb-6">Action de Supervision</h4>
                        <button onclick="adjustBalance()" class="w-full py-4 bg-blue-50 text-blue-600 rounded-2xl font-black text-sm hover:bg-blue-600 hover:text-white transition-all">AJUSTER SOLDE</button>
                        <button class="w-full py-4 bg-red-50 text-red-600 rounded-2xl font-black text-sm hover:bg-red-600 hover:text-white transition-all">BLOQUER COMPTE</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content hidden" id="tab-rdv">
            <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl overflow-hidden">
                <div class="p-10 border-b border-slate-50">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter">Dossier des Consultations</h3>
                </div>
                <div class="p-4">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="px-8 py-6">Patient</th>
                                <th class="px-8 py-6">Type Acte</th>
                                <th class="px-8 py-6">Hôpital / Lieu</th>
                                <th class="px-8 py-6">Date</th>
                                <th class="px-8 py-6 text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($consultations as $consult)
                            <tr class="hover:bg-slate-50 transition-all">
                                <td class="px-8 py-8">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 font-black">
                                            {{ substr($consult->description, -2) }}
                                        </div>
                                        <div class="font-black text-slate-900">{{ str_replace('CONSULTATION:', '', $consult->description) }}</div>
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Consultation</span>
                                </td>
                                <td class="px-8 py-8 font-bold text-slate-500">Cabinet Privé</td>
                                <td class="px-8 py-8 font-bold text-slate-500">{{ $consult->created_at->format('d M Y') }}</td>
                                <td class="px-8 py-8 text-center">
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Terminé</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center text-slate-400">
                                    <i class="bi bi-calendar-x text-4xl mb-4 block opacity-30"></i>
                                    Aucun rendez-vous enregistré pour le moment.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-content hidden" id="tab-prestations">
            <div class="bg-white rounded-[3rem] border border-slate-100 shadow-xl overflow-hidden">
                <div class="p-10 border-b border-slate-50">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter">Services & Catalogue</h3>
                </div>
                <div class="p-4">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <th class="px-8 py-6">Libellé du Service</th>
                                <th class="px-8 py-6">Prix Public</th>
                                <th class="px-8 py-6">Taux Com.</th>
                                <th class="px-8 py-6">Gain Système / Acte</th>
                                <th class="px-8 py-6 text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($specialist->prestations as $prestation)
                            <tr class="hover:bg-slate-50 transition-all">
                                <td class="px-8 py-8">
                                    <div class="font-black text-slate-900">{{ $prestation->name }}</div>
                                    <div class="text-xs text-slate-400">{{ $prestation->description ?? 'Pas de description' }}</div>
                                </td>
                                <td class="px-8 py-8 font-black text-slate-900">{{ number_format($prestation->price) }} FCFA</td>
                                <td class="px-8 py-8 font-bold text-blue-600">{{ $prestation->commission_percentage }}%</td>
                                <td class="px-8 py-8 font-black text-emerald-600">
                                    {{ number_format(($prestation->price * $prestation->commission_percentage) / 100) }} FCFA
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <span class="px-3 py-1 {{ $prestation->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-red-100 text-red-600' }} rounded-lg text-[10px] font-black uppercase tracking-widest">
                                        {{ $prestation->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="py-20 text-center text-slate-400">Catalogue de prestations vide.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-content hidden" id="tab-avis">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Sentiment Analysis Mock -->
                <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-xl">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter mb-8">Satisfaction Globale</h3>
                    <div class="flex items-center gap-6 mb-10">
                        <div class="text-6xl font-black text-slate-900">4.8</div>
                        <div>
                            <div class="flex text-orange-400 text-xl">
                                <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                            </div>
                            <div class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Sut la base de 120 avis</div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        @foreach(['5 étoiles' => 85, '4 étoiles' => 12, '3 étoiles' => 2, '2 étoiles' => 1, '1 étoile' => 0] as $label => $perc)
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-bold text-slate-500 w-16">{{ $label }}</span>
                            <div class="flex-1 h-2 bg-slate-50 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-600 rounded-full" style="width: {{ $perc }}%"></div>
                            </div>
                            <span class="text-xs font-black text-slate-900">{{ $perc }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Reviews Feed -->
                <div class="bg-white p-10 rounded-[3rem] border border-slate-100 shadow-xl overflow-y-auto max-h-[600px] space-y-6">
                    <h3 class="text-2xl font-black text-slate-900 tracking-tighter mb-8">Commentaires Récents</h3>
                    
                    <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-100">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center font-black">AM</div>
                                <div>
                                    <div class="font-bold text-slate-900">Alassane M.</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase">il y a 2 jours</div>
                                </div>
                            </div>
                            <div class="text-orange-400 text-xs"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                        </div>
                        <p class="text-slate-600 text-sm italic">"Excellent praticien, très à l'écoute et ponctuel. Je recommande vivement le Dr."</p>
                    </div>

                    <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-100">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center font-black">SK</div>
                                <div>
                                    <div class="font-bold text-slate-900">Sonia K.</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase">il y a 1 semaine</div>
                                </div>
                            </div>
                            <div class="text-orange-400 text-xs"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-half"></i></div>
                        </div>
                        <p class="text-slate-600 text-sm italic">"Rendez-vous à domicile très professionnel. Communication fluide via l'application."</p>
                    </div>

                    <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-100">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center font-black">JD</div>
                                <div>
                                    <div class="font-bold text-slate-900">Jean D.</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase">il y a 2 semaines</div>
                                </div>
                            </div>
                            <div class="text-orange-400 text-xs"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i></div>
                        </div>
                        <p class="text-slate-600 text-sm italic">"Parfait, rien à redire sur la qualité des soins."</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div id="notification" class="fixed bottom-10 right-10 z-[200] transform transition-all duration-500 translate-y-20 opacity-0">
        <div class="bg-slate-900 text-white px-8 py-4 rounded-3xl shadow-2xl flex items-center gap-4 border border-white/10 backdrop-blur-xl">
            <div id="notification-icon" class="w-10 h-10 rounded-2xl flex items-center justify-center"></div>
            <div>
                <div id="notification-title" class="font-black text-sm uppercase tracking-widest">Notification</div>
                <div id="notification-message" class="text-slate-400 font-medium"></div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            // Remove active class from buttons
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            
            // Show current tab
            document.getElementById('tab-' + tabId).classList.remove('hidden');
            document.getElementById('btn-' + tabId).classList.add('active');
        }

        function showNotification(message, type = 'success') {
            const notif = document.getElementById('notification');
            const iconContainer = document.getElementById('notification-icon');
            const msgContainer = document.getElementById('notification-message');
            
            msgContainer.innerText = message;
            
            if(type === 'success') {
                iconContainer.className = 'w-10 h-10 rounded-2xl flex items-center justify-center bg-emerald-500 text-white';
                iconContainer.innerHTML = '<i class="bi bi-check-lg text-xl"></i>';
            } else if(type === 'error') {
                iconContainer.className = 'w-10 h-10 rounded-2xl flex items-center justify-center bg-red-500 text-white';
                iconContainer.innerHTML = '<i class="bi bi-x-lg text-xl"></i>';
            } else {
                iconContainer.className = 'w-10 h-10 rounded-2xl flex items-center justify-center bg-blue-500 text-white';
                iconContainer.innerHTML = '<i class="bi bi-info-lg text-xl"></i>';
            }

            notif.classList.remove('translate-y-20', 'opacity-0');
            
            setTimeout(() => {
                notif.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }

        function adjustBalance() {
            const amount = prompt('Entrez le montant de l\'ajustement (peut être négatif) :');
            if(amount && !isNaN(amount)) {
                fetch(`/admin-system/specialists/{{ $specialist->id }}/adjust-balance`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ amount: parseFloat(amount), reason: 'Ajustement manuel admin' })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    }
                });
            }
        }
    </script>
</body>
</html>
