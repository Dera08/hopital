<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HospitSIS | Liste des Spécialistes</title>
    
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
                <h1 class="text-5xl font-black text-slate-900 tracking-tighter mb-2">Annuaire de Supervision</h1>
                <p class="text-slate-500 font-medium text-xl">Gestion centralisée des portefeuilles et statuts d'activation.</p>
            </div>
            
            <div class="flex gap-4">
                <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-600">
                        <i class="bi bi-wallet2 text-2xl"></i>
                    </div>
                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Commission Système</div>
                        <div class="text-2xl font-black text-slate-900">{{ number_format($specialists->sum(fn($s) => $s->wallet ? $s->wallet->balance : 0)) }} <span class="text-xs">FCFA</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="bg-white/50 backdrop-blur-md p-4 rounded-[2.5rem] border border-white shadow-xl mb-8 flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="bi bi-search absolute left-6 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" placeholder="Rechercher par nom, ID ou spécialité..." 
                       class="w-full pl-14 pr-6 py-4 bg-white border-2 border-slate-100 rounded-3xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium">
            </div>
            <select class="px-8 py-4 bg-white border-2 border-slate-100 rounded-3xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-bold text-slate-600">
                <option>Tous les statuts</option>
                <option>Actifs</option>
                <option>En attente</option>
                <option>Bloqués</option>
            </select>
        </div>

        <!-- Table Holder -->
        <div class="bg-white rounded-[3rem] border border-slate-200 shadow-2xl overflow-hidden mb-12">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 text-slate-400 text-[11px] font-black uppercase tracking-widest border-b border-slate-100">
                            <th class="px-10 py-8 text-left">Identité Spécialiste</th>
                            <th class="px-10 py-8 text-left">État Financier</th>
                            <th class="px-10 py-8 text-center">Activation Payée</th>
                            <th class="px-10 py-8 text-center">Statut</th>
                            <th class="px-10 py-8 text-right">Actions de Contrôle</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($specialists as $specialist)
                        <tr class="hover:bg-blue-50/40 transition-all duration-300 group">
                            <td class="px-10 py-8">
                                <div class="flex items-center gap-5">
                                    <div class="relative">
                                        <div class="w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-[1.5rem] flex items-center justify-center text-slate-400 group-hover:from-blue-100 group-hover:to-blue-200 group-hover:text-blue-600 transition-all shadow-sm">
                                            <i class="bi bi-person-fill text-3xl"></i>
                                        </div>
                                        @if($specialist->statut === 'actif')
                                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 border-4 border-white rounded-full"></div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-black text-slate-900 text-xl tracking-tight leading-none mb-2">{{ $specialist->nom }} {{ $specialist->prenom }}</div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-[11px] text-blue-600 font-black uppercase tracking-tighter bg-blue-50 px-2 py-1 rounded-lg">
                                                {{ $specialist->specialite ?? 'Médecin' }}
                                            </span>
                                            <span class="text-[11px] text-slate-400 font-bold uppercase tracking-tighter">ID: #{{ $specialist->id }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-10 py-8">
                                <div class="text-3xl font-black text-slate-900 tracking-tighter">
                                    {{ number_format($specialist->wallet ? $specialist->wallet->balance : 0) }}
                                    <span class="text-sm font-bold text-slate-400 ml-1">FCFA</span>
                                </div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Solde en compte</div>
                            </td>
                            <td class="px-10 py-8 text-center">
                                @if($specialist->wallet && $specialist->wallet->is_activated)
                                    <div class="bg-emerald-50 text-emerald-700 px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest border-2 border-emerald-100/50 flex flex-col items-center">
                                        <div class="flex items-center gap-2">
                                            <i class="bi bi-patch-check-fill text-xl"></i> PAYÉ
                                        </div>
                                        <span class="text-[9px] opacity-70 mt-1">{{ $specialist->wallet->activated_at ? $specialist->wallet->activated_at->format('d M Y') : 'N/A' }}</span>
                                    </div>
                                @else
                                    <div class="bg-orange-50 text-orange-600 px-4 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest border-2 border-orange-100/50 flex items-center justify-center gap-2 italic">
                                        <i class="bi bi-hourglass-split text-xl"></i> EN ATTENTE
                                    </div>
                                @endif
                            </td>
                            <td class="px-10 py-8 text-center">
                                <span class="px-5 py-2.5 rounded-2xl text-[10px] font-black uppercase tracking-widest {{ $specialist->statut === 'actif' ? 'bg-green-100 text-green-800 border-2 border-green-200' : 'bg-red-100 text-red-800 border-2 border-red-200' }}">
                                    {{ strtoupper($specialist->statut ?? 'INACTIF') }}
                                </span>
                            </td>
                            <td class="px-10 py-8 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('superadmin.specialists.show', $specialist->id) }}" class="w-12 h-12 flex items-center justify-center text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white rounded-2xl transition-all border-2 border-transparent hover:border-blue-100" title="Voir Dashboard complet">
                                        <i class="bi bi-eye-fill text-xl"></i>
                                    </a>
                                    <button onclick="viewHistory({{ $specialist->id }})" class="w-12 h-12 flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-2xl transition-all border-2 border-transparent hover:border-blue-100" title="Historique">
                                        <i class="bi bi-graph-up-arrow text-xl"></i>
                                    </button>
                                    <button onclick="manageWallet({{ $specialist->id }})" class="w-12 h-12 flex items-center justify-center text-slate-400 hover:text-purple-600 hover:bg-purple-50 rounded-2xl transition-all border-2 border-transparent hover:border-purple-100" title="Portefeuille">
                                        <i class="bi bi-wallet2 text-xl"></i>
                                    </button>
                                    <button onclick="blockAccount({{ $specialist->id }})" class="w-12 h-12 flex items-center justify-center text-red-300 hover:text-red-600 hover:bg-red-50 rounded-2xl transition-all border-2 border-transparent hover:border-red-100" title="Action">
                                        <i class="bi bi-three-dots-vertical text-xl"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-10 py-32 text-center text-slate-400">
                                <div class="bg-slate-50 border-4 border-dashed border-slate-200 rounded-[4rem] p-20 inline-block">
                                    <i class="bi bi-people text-8xl text-slate-200 mb-8 block"></i>
                                    <div class="text-3xl font-black text-slate-900 mb-2">Aucun spécialiste trouvé</div>
                                    <p class="text-lg">Votre annuaire est actuellement vide.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-10 py-10 bg-slate-50 border-t border-slate-100">
                {!! $specialists->links() !!}
            </div>
        </div>
</div>

<!-- Modal Historique -->
<div id="historyModal" class="fixed inset-0 z-[150] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate__animated animate__zoomIn animate__faster">
        <div class="p-10 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="text-3xl font-black text-slate-900 tracking-tighter">Historique Transactions</h3>
                <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-1" id="historySpecialistName">Nom du spécialiste</p>
            </div>
            <button onclick="closeModal('historyModal')" class="w-12 h-12 flex items-center justify-center bg-white border border-slate-200 rounded-2xl text-slate-400 hover:text-slate-900 transition-all">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="p-10 max-h-[60vh] overflow-y-auto" id="historyContent">
            <!-- Transactions loaded here -->
        </div>
    </div>
</div>

<!-- Modal Portefeuille -->
<div id="walletModal" class="fixed inset-0 z-[150] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-[3rem] shadow-2xl overflow-hidden animate__animated animate__zoomIn animate__faster">
        <div class="p-10 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-3xl font-black text-slate-900 tracking-tighter">Gérer Portefeuille</h3>
            <button onclick="closeModal('walletModal')" class="w-12 h-12 flex items-center justify-center bg-slate-50 rounded-2xl text-slate-400 hover:text-slate-900 transition-all">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="p-10">
            <div class="bg-blue-600 rounded-[2rem] p-8 text-white mb-8 shadow-xl shadow-blue-200 relative overflow-hidden">
                <div class="relative z-10">
                    <div class="text-xs font-bold uppercase tracking-widest opacity-80 mb-2">Solde Actuel</div>
                    <div class="text-5xl font-black tracking-tighter mb-4" id="walletBalance">0 FCFA</div>
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 bg-white/20 rounded-lg text-[10px] font-black uppercase tracking-widest" id="walletStatusBadge">ACTIF</span>
                    </div>
                </div>
                <i class="bi bi-wallet2 absolute -right-4 -bottom-4 text-9xl opacity-10"></i>
            </div>

            <div class="space-y-4">
                <button onclick="adjustBalance()" class="w-full py-4 bg-white border-2 border-slate-100 rounded-2xl font-black text-slate-700 hover:bg-slate-50 hover:border-slate-200 transition-all flex items-center justify-center gap-3">
                    <i class="bi bi-plus-circle text-blue-600"></i> AJUSTER LE SOLDE
                </button>
                <div id="walletBlockButtonContainer">
                    <!-- Toggle block button -->
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    let currentSpecialistId = null;

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.getElementById(id).classList.add('flex');
    }

    function viewHistory(id) {
        currentSpecialistId = id;
        fetch(`/admin-system/specialists/${id}/details`)
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('historySpecialistName').innerText = data.specialist.name;
                    const content = document.getElementById('historyContent');
                    
                    if(data.transactions.length === 0) {
                        content.innerHTML = '<div class="text-center py-12 text-slate-400 font-bold">Aucune transaction trouvée</div>';
                    } else {
                        content.innerHTML = `
                            <div class="space-y-4">
                                ${data.transactions.map(t => `
                                    <div class="flex items-center justify-between p-6 bg-slate-50 rounded-2xl border border-slate-100">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-slate-400">
                                                <i class="bi bi-receipt"></i>
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-900">${t.description}</div>
                                                <div class="text-[10px] text-slate-400 font-bold uppercase">${new Date(t.created_at).toLocaleDateString('fr-FR', {day:'numeric', month:'long', year:'numeric', hour:'2-digit', minute:'2-digit'})}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="font-black text-slate-900">${numberFormat(t.amount)} FCFA</div>
                                            <div class="text-[9px] text-slate-400 font-bold uppercase">Net: ${numberFormat(t.net_income)}</div>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    }
                    openModal('historyModal');
                } else {
                    showNotification('Erreur de chargement', 'error');
                }
            });
    }

    function manageWallet(id) {
        currentSpecialistId = id;
        fetch(`/admin-system/specialists/${id}/details`)
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    const wallet = data.specialist.wallet;
                    document.getElementById('walletBalance').innerText = numberFormat(wallet ? wallet.balance : 0) + ' FCFA';
                    
                    const statusBadge = document.getElementById('walletStatusBadge');
                    if(wallet && wallet.is_blocked) {
                        statusBadge.className = 'px-3 py-1 bg-red-500 rounded-lg text-[10px] font-black uppercase tracking-widest';
                        statusBadge.innerText = 'BLOQUÉ';
                    } else {
                        statusBadge.className = 'px-3 py-1 bg-emerald-500 rounded-lg text-[10px] font-black uppercase tracking-widest';
                        statusBadge.innerText = 'ACTIF';
                    }

                    const blockBtnContainer = document.getElementById('walletBlockButtonContainer');
                    const isBlocked = wallet && wallet.is_blocked;
                    blockBtnContainer.innerHTML = `
                        <button onclick="toggleWalletBlock(${data.specialist.id}, ${isBlocked})" class="w-full py-4 ${isBlocked ? 'bg-emerald-50 text-emerald-600 border-emerald-100 hover:bg-emerald-600 hover:text-white' : 'bg-red-50 text-red-600 border-red-100 hover:bg-red-600 hover:text-white'} border-2 rounded-2xl font-black transition-all flex items-center justify-center gap-3">
                            <i class="bi ${isBlocked ? 'bi-shield-check' : 'bi-shield-x'}"></i> ${isBlocked ? 'DÉBLOQUER LE PORTEFEUILLE' : 'BLOQUER LE PORTEFEUILLE'}
                        </button>
                    `;

                    openModal('walletModal');
                }
            });
    }

    function toggleWalletBlock(id, currentlyBlocked) {
        const action = currentlyBlocked ? 'unblock-wallet' : 'block-wallet';
        const confirmMsg = currentlyBlocked ? 'Voulez-vous débloquer ce portefeuille ?' : 'Voulez-vous bloquer ce portefeuille ?';
        
        if(confirm(confirmMsg)) {
            fetch(`/admin-system/specialists/${id}/${action}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    showNotification(data.message, 'success');
                    closeModal('walletModal');
                    setTimeout(() => location.reload(), 1000);
                }
            });
        }
    }

    function adjustBalance() {
        const amount = prompt('Entrez le montant de l\'ajustement (peut être négatif) :');
        if(amount && !isNaN(amount)) {
            fetch(`/admin-system/specialists/${currentSpecialistId}/adjust-balance`, {
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
                    closeModal('walletModal');
                    setTimeout(() => location.reload(), 1000);
                }
            });
        }
    }

    function blockAccount(id) {
        if(confirm('Voulez-vous vraiment bloquer ce compte spécialiste ?')) {
            fetch(`/admin-system/specialists/${id}/block-wallet`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    showNotification(data.message, 'success');
                    location.reload();
                } else {
                    showNotification('Erreur lors du blocage', 'error');
                }
            });
        }
    }

</script>

    @include('superadmin.partials.scripts')
</body>
</html>

