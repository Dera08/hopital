<div id="tab-subscription-plans" class="tab-pane animate-in slide-in-from-left-8 duration-500">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 text-left gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter">Catalogue des Forfaits Hôpitaux</h2>
            <p class="text-slate-500 font-medium">Définissez les plans d'abonnement SaaS pour vos hôpitaux.</p>
        </div>
        <button onclick="openSubscriptionPlanModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-bold transition shadow-xl shadow-blue-200 flex items-center justify-center gap-3 group">
            <i class="bi bi-plus-lg group-hover:scale-125 transition-transform"></i>
            Nouveau Plan
        </button>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden text-left">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-400 text-[11px] font-black uppercase tracking-widest border-b border-slate-100">
                        <th class="px-8 py-6">Plan</th>
                        <th class="px-8 py-6">Prix</th>
                        <th class="px-8 py-6">Durée</th>
                        <th class="px-8 py-6">Fonctionnalités</th>
                        <th class="px-8 py-6 text-center">Statut</th>
                        <th class="px-8 py-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50" id="subscriptionPlansTable">
                    <!-- Plans will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
