@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen animate-fade-in">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Gestion de l'Abonnement</h1>
            <p class="text-gray-600 text-lg">{{ auth()->user()->hospital->name ?? 'Hôpital' }}</p>
            <p class="text-sm text-gray-500 mt-1">Choisissez et gérez votre plan d'abonnement</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('profile.edit') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour au Profil
            </a>
        </div>
    </div>

    <!-- Plan actuel -->
    @if($currentPlan)
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-green-500 to-teal-500 px-6 py-4">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Plan Actuel
            </h3>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-2xl font-bold text-gray-800">{{ $currentPlan->name }}</h4>
                    <p class="text-gray-600 mt-1">{{ number_format($currentPlan->price, 2) }} ₣ / {{ $currentPlan->duration_unit }}</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                    Actif
                </span>
            </div>
            <div class="mt-4">
                <h5 class="font-semibold text-gray-800 mb-2">Fonctionnalités incluses :</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    @foreach($currentPlan->features ?? [] as $feature)
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $feature }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-gray-500 to-gray-600 px-6 py-4">
            <h3 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                </svg>
                Aucun Plan Actif
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-600">Vous utilisez actuellement les fonctionnalités de base gratuites.</p>
        </div>
    </div>
    @endif

    <!-- Plans disponibles -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Plans Disponibles</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($availablePlans as $plan)
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300 {{ $currentPlan && $currentPlan->id == $plan->id ? 'ring-2 ring-blue-500' : '' }}">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-500 px-6 py-4">
                    <h3 class="text-xl font-bold text-white">{{ $plan->name }}</h3>
                    <p class="text-blue-100">{{ number_format($plan->price, 2) }} ₣ / {{ $plan->duration_unit }}</p>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <h4 class="font-semibold text-gray-800 mb-2">Fonctionnalités :</h4>
                        <ul class="space-y-1">
                            @foreach($plan->features ?? [] as $feature)
                                <li class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if($currentPlan && $currentPlan->id == $plan->id)
                        <div class="text-center">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Plan Actuel
                            </span>
                        </div>
                    @else
                        <button type="button" onclick="selectPlan({{ $plan->id }}, {{ $plan->price }}, '{{ $plan->name }}')" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            Choisir ce Plan
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Messages flash -->
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <ul class="text-sm text-red-800">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<!-- Payment Modal -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Paiement du Plan</h3>
                <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-600" id="modalDescription">Vous allez souscrire au plan sélectionné.</p>
                <div class="mt-2 p-3 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold" id="planName"></span>
                        <span class="font-bold text-lg" id="planPrice"></span>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label for="paymentMethod" class="block text-sm font-semibold text-gray-700 mb-2">Mode de Paiement</label>
                <select id="paymentMethod" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="card">Carte de Crédit</option>
                    <option value="mobile">Mobile Money</option>
                    <option value="bank">Virement Bancaire</option>
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closePaymentModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors">
                    Annuler
                </button>
                <button onclick="processPayment()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Payer Maintenant
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Provider Selection Modal -->
<div id="providerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Choisir un Fournisseur de Paiement</h3>
                <button onclick="closeProviderModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-600">Sélectionnez votre fournisseur de paiement mobile.</p>
            </div>
            <div class="grid grid-cols-1 gap-4">
                <button onclick="selectProvider('Wave')" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-white font-bold text-lg">W</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Wave</h4>
                        <p class="text-sm text-gray-600">Paiement mobile rapide</p>
                    </div>
                </button>
                <button onclick="selectProvider('Orange Money')" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-white font-bold text-lg">O</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Orange Money</h4>
                        <p class="text-sm text-gray-600">Service de paiement Orange</p>
                    </div>
                </button>
                <button onclick="selectProvider('MTN Mobile Money')" class="flex items-center p-4 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mr-4">
                        <span class="text-white font-bold text-lg">M</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">MTN Mobile Money</h4>
                        <p class="text-sm text-gray-600">Paiement MTN sécurisé</p>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Facture Modal -->
<div id="factureModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Facture de Paiement</h3>
                <button onclick="closeFactureModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mb-4">
                <p class="text-sm text-gray-600">Récapitulatif de votre commande</p>
                <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold">Plan:</span>
                        <span id="facturePlanName"></span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold">Fournisseur:</span>
                        <span id="factureProvider"></span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-lg">Total:</span>
                        <span class="font-bold text-lg" id="facturePlanPrice"></span>
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="closeFactureModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition-colors">
                    Annuler
                </button>
                <button onclick="confirmPayment()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let selectedPlanId = null;
let selectedPlanPrice = null;
let selectedPlanName = null;

function selectPlan(planId, price, name) {
    selectedPlanId = planId;
    selectedPlanPrice = price;
    selectedPlanName = name;

    if (price == 0) {
        // Plan gratuit, changer directement
        changePlan(planId);
    } else {
        // Plan payant, afficher le modal de paiement
        document.getElementById('planName').textContent = name;
        document.getElementById('planPrice').textContent = price + ' ₣';
        document.getElementById('paymentModal').classList.remove('hidden');
    }
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
    // Keep the selected plan variables for the next modals
}

function processPayment() {
    // Fermer le modal de paiement et ouvrir le modal des fournisseurs
    closePaymentModal();
    openProviderModal();
}

function openProviderModal() {
    document.getElementById('providerModal').classList.remove('hidden');
}

function closeProviderModal() {
    document.getElementById('providerModal').classList.add('hidden');
}

function selectProvider(provider) {
    closeProviderModal();
    openFactureModal(provider);
}

function openFactureModal(provider) {
    document.getElementById('facturePlanName').textContent = selectedPlanName;
    document.getElementById('facturePlanPrice').textContent = selectedPlanPrice + ' ₣';
    document.getElementById('factureProvider').textContent = provider;
    document.getElementById('factureModal').classList.remove('hidden');
}

function closeFactureModal() {
    document.getElementById('factureModal').classList.add('hidden');
}

function confirmPayment() {
    closeFactureModal();
    // Ici, vous pouvez intégrer une vraie passerelle de paiement
    // Pour l'instant, on simule le paiement et on change le plan
    alert('Paiement traité avec succès. Le plan sera activé.');
    changePlan(selectedPlanId);
}

function changePlan(planId) {
    // Créer un formulaire temporaire et le soumettre
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.subscription.change") }}';

    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';

    const planInput = document.createElement('input');
    planInput.type = 'hidden';
    planInput.name = 'plan_id';
    planInput.value = planId;

    form.appendChild(csrfToken);
    form.appendChild(planInput);
    document.body.appendChild(form);
    form.submit();
}
</script>

@endsection