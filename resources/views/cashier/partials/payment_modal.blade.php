{{-- resources/views/cashier/partials/payment_modal.blade.php --}}

<div id="paymentModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden items-center justify-center z-[100] p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-y-auto max-h-[90vh] transform transition-all">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight">Finaliser l'encaissement</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="paymentForm" method="POST" action="" class="p-6">
            @csrf
            <div class="space-y-6">
                {{-- Résumé du montant --}}
                <div class="bg-blue-600 rounded-2xl p-5 text-white shadow-lg shadow-blue-200">
                    <p class="text-blue-100 text-xs font-bold uppercase tracking-widest mb-1">Total à encaisser</p>
                    <p class="text-3xl font-black"><span id="modalAmount">0</span> F CFA</p>
                    <div class="mt-3 pt-3 border-t border-white/20">
                        <p class="text-sm font-medium">
                            <i class="fas fa-user-circle mr-2 text-blue-200"></i>
                            <span id="modalPatientName"></span>
                        </p>
                    </div>
                </div>

                {{-- Choix du mode de paiement --}}
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-3">Mode de règlement</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex flex-col items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 group">
                            <input type="radio" name="payment_method" value="Espèces" class="hidden" checked onchange="toggleMobileMoneyOptions(false)">
                            <i class="fas fa-money-bill-wave text-xl mb-2 text-gray-400 group-has-[:checked]:text-blue-600"></i>
                            <span class="text-xs font-bold text-gray-600 group-has-[:checked]:text-blue-800">Espèces</span>
                        </label>
                        <label class="relative flex flex-col items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 group">
                            <input type="radio" name="payment_method" value="Mobile Money" class="hidden" onchange="toggleMobileMoneyOptions(true)">
                            <i class="fas fa-mobile-alt text-xl mb-2 text-gray-400 group-has-[:checked]:text-blue-600"></i>
                            <span class="text-xs font-bold text-gray-600 group-has-[:checked]:text-blue-800">Mobile Money</span>
                        </label>
                    </div>
                </div>

                {{-- Options Mobile Money --}}
                <div id="mobileMoneyOptions" class="hidden space-y-3 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Choisir l'opérateur</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="flex items-center gap-2 p-2 border border-gray-200 rounded-xl cursor-pointer hover:bg-white has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="mobile_operator" value="wave" class="w-4 h-4 text-blue-600">
                            <span class="text-xs font-bold text-gray-700">Wave</span>
                        </label>
                        <label class="flex items-center gap-2 p-2 border border-gray-200 rounded-xl cursor-pointer hover:bg-white has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="mobile_operator" value="orange" class="w-4 h-4 text-blue-600">
                            <span class="text-xs font-bold text-gray-700">Orange</span>
                        </label>
                        <label class="flex items-center gap-2 p-2 border border-gray-200 rounded-xl cursor-pointer hover:bg-white has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="mobile_operator" value="mtn" class="w-4 h-4 text-blue-600">
                            <span class="text-xs font-bold text-gray-700">MTN</span>
                        </label>
                        <label class="flex items-center gap-2 p-2 border border-gray-200 rounded-xl cursor-pointer hover:bg-white has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                            <input type="radio" name="mobile_operator" value="moov" class="w-4 h-4 text-blue-600">
                            <span class="text-xs font-bold text-gray-700">Moov</span>
                        </label>
                    </div>
                    <div class="mt-4">
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Numéro de téléphone</label>
                        <input type="text" name="mobile_number" placeholder="07xxxxxxxx" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none text-sm font-bold transition-all">
                    </div>
                </div>

                <input type="hidden" name="amount_paid" id="hiddenAmount">

                <button type="submit" id="submitPaymentBtn" class="w-full bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-black transition-all shadow-xl flex items-center justify-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    VALIDER L'ENCAISSEMENT
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPaymentModal(id, name, amount, type = 'appointment') {
        document.getElementById('modalPatientName').innerText = name;
        document.getElementById('modalAmount').innerText = amount.toLocaleString();
        document.getElementById('hiddenAmount').value = amount;
        
        let form = document.getElementById('paymentForm');
        
        if (type === 'walk-in') {
            form.action = `/cashier/walk-in/${id}/validate-payment`;
        } else if (type === 'lab_request') {
            form.action = `/cashier/lab-requests/${id}/pay`;
        } else {
            form.action = `/cashier/appointments/${id}/validate-payment`;
        }
        
        const modal = document.getElementById('paymentModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('paymentModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function toggleMobileMoneyOptions(show) {
        const options = document.getElementById('mobileMoneyOptions');
        if (show) {
            options.classList.remove('hidden');
            // Make operator and number required if showing
            document.querySelectorAll('input[name="mobile_operator"]').forEach(el => el.required = true);
            document.querySelector('input[name="mobile_number"]').required = true;
        } else {
            options.classList.add('hidden');
            document.querySelectorAll('input[name="mobile_operator"]').forEach(el => el.required = false);
            document.querySelector('input[name="mobile_number"]').required = false;
        }
    }

    document.getElementById('paymentForm').onsubmit = function() {
        const btn = document.getElementById('submitPaymentBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner animate-spin"></i> TRAITEMENT...';
    };
</script>