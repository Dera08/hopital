<div id="tab-overview" class="tab-pane active animate-in fade-in duration-500">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 text-left">
        <div class="card-stat bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-50 p-3 rounded-2xl text-blue-600"><i class="bi bi-cash-stack text-xl"></i></div>
                <span class="text-green-600 text-[10px] font-black bg-green-50 px-2 py-1 rounded-full uppercase tracking-wider">+{{ number_format($stats['monthly_saas_revenue']) }} ce mois</span>
            </div>
            <div class="text-4xl font-black text-slate-900 tracking-tighter">{{ number_format($stats['total_saas_revenue']) }}</div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Revenus SaaS Total (FCFA)</div>
        </div>
        <div class="card-stat bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-50 p-3 rounded-2xl text-purple-600"><i class="bi bi-percent text-xl"></i></div>
                <span class="text-purple-600 text-[10px] font-black bg-purple-50 px-2 py-1 rounded-full uppercase tracking-wider">+{{ number_format($stats['monthly_commissions']) }} ce mois</span>
            </div>
            <div class="text-4xl font-black text-slate-900 tracking-tighter">{{ number_format($stats['total_commissions']) }}</div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Total Commissions (FCFA)</div>
        </div>
        <div class="card-stat bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-50 p-3 rounded-2xl text-green-600"><i class="bi bi-people text-xl"></i></div>
                <span class="text-slate-400 text-[10px] font-black bg-slate-50 px-2 py-1 rounded-full uppercase">Live Sync</span>
            </div>
            <div class="text-4xl font-black text-slate-900 tracking-tighter">{{ number_format($stats['total_patients']) }}</div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Patients Globaux</div>
        </div>
        <div class="card-stat bg-white rounded-3xl p-6 border border-slate-200/60 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-orange-50 p-3 rounded-2xl text-orange-600"><i class="bi bi-clock-history text-xl"></i></div>
                <span class="text-orange-600 text-[10px] font-black bg-orange-50 px-2 py-1 rounded-full uppercase">Urgent</span>
            </div>
            <div class="text-4xl font-black text-slate-900 tracking-tighter text-orange-600">{{ $stats['pending_validations'] }}</div>
            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Validations en attente</div>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm text-left relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-5">
            <i class="bi bi-cpu text-[8rem]"></i>
        </div>
        <h3 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
            <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
            État de Santé du Système
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="p-6 bg-slate-50 rounded-[1.5rem] border border-slate-100 group hover:border-blue-200 transition-colors">
                <i class="bi bi-database-check text-green-500 text-3xl mb-3 block"></i>
                <span class="text-sm font-bold text-slate-900 block">Core Database</span>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] text-green-600 font-black uppercase tracking-tighter">Opérationnel</span>
                </div>
            </div>
            <div class="p-6 bg-slate-50 rounded-[1.5rem] border border-slate-100 group hover:border-blue-200 transition-colors">
                <i class="bi bi-hdd-network text-green-500 text-3xl mb-3 block"></i>
                <span class="text-sm font-bold text-slate-900 block">Clusters SaaS</span>
                <div class="flex items-center gap-2 mt-1">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] text-green-600 font-black uppercase tracking-tighter">Opérationnel</span>
                </div>
            </div>
        </div>
    </div>
</div>
