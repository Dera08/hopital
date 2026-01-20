<div id="tab-commission-rates" class="tab-pane animate-in slide-in-from-right-8 duration-500">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 text-left gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter">Configuration des Commissions Spécialistes</h2>
            <p class="text-slate-500 font-medium">Définissez les règles de prélèvement pour les spécialistes externes.</p>
        </div>
        <button onclick="openCommissionRateModal()" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-4 rounded-2xl font-bold transition shadow-xl shadow-purple-200 flex items-center justify-center gap-3 group">
            <i class="bi bi-plus-lg group-hover:scale-125 transition-transform"></i>
            Nouvelle Règle
        </button>
    </div>

    <div class="grid md:grid-cols-2 gap-8 mb-8">
        <div class="bg-gradient-to-br from-purple-600 to-purple-700 p-8 rounded-[2rem] text-white shadow-2xl">
            <h4 class="font-black uppercase text-[11px] tracking-[0.2em] mb-4 opacity-80">Frais d'Activation</h4>
            <div class="text-4xl font-black mb-2 tracking-tighter">{{ number_format($stats['activation_fee']) }} <span class="text-lg font-medium opacity-50">FCFA</span></div>
            <p class="text-sm opacity-80">Montant fixe à la première recharge</p>
        </div>
        <div class="bg-gradient-to-br from-green-600 to-green-700 p-8 rounded-[2rem] text-white shadow-2xl">
            <h4 class="font-black uppercase text-[11px] tracking-[0.2em] mb-4 opacity-80">Commission Moyenne</h4>
            <div class="text-4xl font-black mb-2 tracking-tighter">{{ $stats['average_commission'] }}% <span class="text-lg font-medium opacity-50">par acte</span></div>
            <p class="text-sm opacity-80">Taux de prélèvement sur les prestations</p>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-8 text-left">
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="commissionRatesContainer">
            <!-- Rates will be loaded here -->
        </div>
    </div>
</div>
