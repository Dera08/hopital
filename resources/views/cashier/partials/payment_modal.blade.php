{{-- resources/views/cashier/partials/payment_modal.blade.php --}}

<div id="paymentModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden items-center justify-center z-[100] p-4">
    <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
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
                            <input type="radio" name="payment_method" value="Espèces" class="hidden" checked>
                            <i class="fas fa-money-bill-wave text-xl mb-2 text-gray-400 group-has-[:checked]:text-blue-600"></i>
                            <span class="text-xs font-bold text-gray-600 group-has-[:checked]:text-blue-800">Espèces</span>
                        </label>
                        <label class="relative flex flex-col items-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 group">
                            <input type="radio" name="payment_method" value="Mobile Money" class="hidden">
                            <i class="fas fa-mobile-alt text-xl mb-2 text-gray-400 group-has-[:checked]:text-blue-600"></i>
                            <span class="text-xs font-bold text-gray-600 group-has-[:checked]:text-blue-800">Mobile Money</span>
                        </label>
                    </div>
                </div>

                <input type="hidden" name="amount_paid" id="hiddenAmount">

                <button type="submit" class="w-full bg-gray-900 text-white font-black py-4 rounded-2xl hover:bg-black transition-all shadow-xl flex items-center justify-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    VALIDER L'ENCAISSEMENT
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPaymentModal(id, name, amount) {
        document.getElementById('modalPatientName').innerText = name;
        document.getElementById('modalAmount').innerText = amount.toLocaleString();
        document.getElementById('hiddenAmount').value = amount;
        
        let form = document.getElementById('paymentForm');
        // Assurez-vous que cette route existe dans votre web.php
        form.action = `/cashier/appointments/${id}/validate-payment`;
        
        const modal = document.getElementById('paymentModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('paymentModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>