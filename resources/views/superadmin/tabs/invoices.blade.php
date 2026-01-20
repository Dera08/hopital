<div id="tab-invoices" class="tab-pane animate-in slide-in-from-left-8 duration-500">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 text-left gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tighter">Factures & Revenus des Hôpitaux</h2>
            <p class="text-slate-500 font-medium">Suivez tous les revenus et paiements des hôpitaux.</p>
        </div>
        <button onclick="refreshInvoicesData()" class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-3xl font-bold transition shadow-xl shadow-green-200 flex items-center justify-center gap-3">
            <i class="bi bi-arrow-clockwise"></i>
            Actualiser
        </button>
    </div>

    <!-- Revenue Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 text-left" id="invoiceStats">
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm card-stat">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-100 p-3 rounded-2xl">
                    <i class="bi bi-cash-coin text-green-600 text-2xl"></i>
                </div>
                <span class="text-green-600 text-sm font-bold">TOTAL REVENUS</span>
            </div>
            <div class="text-3xl font-black text-slate-900" id="totalRevenue">0 ₣</div>
            <p class="text-slate-500 text-sm font-medium">Revenus totaux des hôpitaux</p>
        </div>

        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm card-stat">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-100 p-3 rounded-2xl">
                    <i class="bi bi-check-circle text-blue-600 text-2xl"></i>
                </div>
                <span class="text-blue-600 text-sm font-bold">PAYÉ</span>
            </div>
            <div class="text-3xl font-black text-slate-900" id="totalPaid">0 ₣</div>
            <p class="text-slate-500 text-sm font-medium">Montant total payé</p>
        </div>

        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm card-stat">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-orange-100 p-3 rounded-2xl">
                    <i class="bi bi-clock text-orange-600 text-2xl"></i>
                </div>
                <span class="text-orange-600 text-sm font-bold">EN ATTENTE</span>
            </div>
            <div class="text-3xl font-black text-slate-900" id="totalPending">0 ₣</div>
            <p class="text-slate-500 text-sm font-medium">Montant en attente de paiement</p>
        </div>

        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm card-stat">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-100 p-3 rounded-2xl">
                    <i class="bi bi-receipt text-purple-600 text-2xl"></i>
                </div>
                <span class="text-purple-600 text-sm font-bold">FACTURES</span>
            </div>
            <div class="text-3xl font-black text-slate-900" id="totalInvoices">0</div>
            <div class="flex gap-4 mt-2">
                <div class="text-center">
                    <div class="text-lg font-bold text-green-600" id="paidInvoices">0</div>
                    <div class="text-xs text-slate-500">Payées</div>
                </div>
                <div class="text-center">
                    <div class="text-lg font-bold text-red-600" id="pendingInvoices">0</div>
                    <div class="text-xs text-slate-500">En attente</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden text-left">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-400 text-[11px] font-black uppercase tracking-widest border-b border-slate-100">
                        <th class="px-8 py-6">Facture</th>
                        <th class="px-8 py-6">Hôpital</th>
                        <th class="px-8 py-6">Patient</th>
                        <th class="px-8 py-6 text-right">Montant Total</th>
                        <th class="px-8 py-6 text-right">Payé</th>
                        <th class="px-8 py-6 text-right">Restant</th>
                        <th class="px-8 py-6 text-center">Statut</th>
                        <th class="px-8 py-6">Date</th>
                    </tr>
                </thead>
                <tbody id="invoicesTable">
                    <!-- Invoices will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
