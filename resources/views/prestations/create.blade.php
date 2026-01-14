@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen animate-fade-in">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Ajouter une nouvelle Prestation</h1>
            <p class="text-gray-600">Configuration des prestations médicales de {{ auth()->user()->hospital->name }}</p>
        </div>
        <a href="{{ route('prestations.index') }}" class="bg-white text-gray-700 px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition shadow-sm">
            Retour
        </a>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
            <form method="POST" action="{{ route('prestations.store') }}">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Nom de la Prestation</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                    @error('name') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="code" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Code (ex: CONS-CARDIO, EXAM-RAD)</label>
                    <input id="code" type="text" name="code" value="{{ old('code') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                    @error('code') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="category" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Catégorie</label>
                        <select id="category" name="category" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                            <option value="">Sélectionner une catégorie</option>
                            <option value="consultation" {{ old('category') == 'consultation' ? 'selected' : '' }}>Consultation</option>
                            <option value="examen" {{ old('category') == 'examen' ? 'selected' : '' }}>Examen</option>
                            <option value="soins" {{ old('category') == 'soins' ? 'selected' : '' }}>Soins</option>
                            <option value="medicament" {{ old('category') == 'medicament' ? 'selected' : '' }}>Médicament</option>
                            <option value="hospitalisation" {{ old('category') == 'hospitalisation' ? 'selected' : '' }}>Hospitalisation</option>
                        </select>
                        @error('category') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="service_id" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Service Associé</label>
                        <select id="service_id" name="service_id" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                            <option value="">Sélectionner un service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @error('service_id') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <label for="is_pack" class="block text-sm font-semibold text-blue-800 uppercase mb-2">Type de Prestation</label>
                    <select id="is_pack" name="is_pack" onchange="togglePackDetails()"
                        class="w-full px-4 py-3 rounded-xl border border-blue-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none bg-white font-bold">
                        <option value="0" {{ old('is_pack') == '0' ? 'selected' : '' }}>Prestation Individuelle</option>
                        <option value="1" {{ old('is_pack') == '1' ? 'selected' : '' }}>Pack (Forfait regroupant plusieurs actes)</option>
                    </select>
                </div>

                <div id="pack_selection_area" class="mb-6 p-6 bg-white border-2 border-dashed border-blue-200 rounded-xl" style="display: none;">
                    <h3 class="text-blue-700 font-bold mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Actes inclus dans le pack
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-2">
                        @foreach($prestations as $p)
                            <div class="flex items-center p-3 rounded-lg hover:bg-gray-50 border border-transparent hover:border-gray-200 transition">
                                <input type="checkbox" name="pack_items[]" value="{{ $p->id }}" id="p_{{ $p->id }}" 
                                    class="w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <label for="p_{{ $p->id }}" class="ml-3 cursor-pointer">
                                    <span class="block text-sm font-medium text-gray-800">{{ $p->name }}</span>
                                    <span class="block text-xs text-blue-600 font-bold">{{ number_format($p->price) }} CFA</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <label for="price" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Prix total (CFA)</label>
                    <input id="price" type="number" name="price" value="{{ old('price') }}" step="1" min="0" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none font-bold text-xl text-green-700">
                    @error('price') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Description</label>
                    <textarea id="description" name="description" rows="3"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="payment_timing" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Moment du Paiement</label>
                        <select id="payment_timing" name="payment_timing" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                            <option value="before" {{ old('payment_timing') == 'before' ? 'selected' : '' }}>Avant la prestation</option>
                            <option value="after" {{ old('payment_timing') == 'after' ? 'selected' : '' }}>Après la prestation</option>
                            <option value="upon_completion" {{ old('payment_timing') == 'upon_completion' ? 'selected' : '' }}>À la fin</option>
                        </select>
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Priorité</label>
                        <select id="priority" name="priority" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                            <option value="low">Faible</option>
                            <option value="medium" selected>Moyenne</option>
                            <option value="high">Élevée</option>
                            <option value="urgent">Urgente</option>
                        </select>
                    </div>
                </div>

                <div class="flex items-center space-x-6 mb-8">
                    <div class="flex items-center">
                        <input id="requires_approval" type="checkbox" name="requires_approval" value="1" class="h-5 w-5 text-blue-600 rounded">
                        <label for="requires_approval" class="ml-2 text-sm text-gray-700">Nécessite approbation</label>
                    </div>
                    <div class="flex items-center">
                        <input id="is_emergency" type="checkbox" name="is_emergency" value="1" class="h-5 w-5 text-red-600 rounded">
                        <label for="is_emergency" class="ml-2 text-sm text-gray-700 text-red-600 font-bold">Urgence médicale</label>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8">
                    <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white px-10 py-4 rounded-xl font-bold shadow-lg hover:shadow-blue-200 transition-all active:scale-95">
                        Enregistrer la Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePackDetails() {
        const isPack = document.getElementById('is_pack').value;
        const area = document.getElementById('pack_selection_area');
        area.style.display = (isPack === "1") ? 'block' : 'none';
    }

    // Gérer l'affichage au chargement si erreur de validation
    window.onload = togglePackDetails;
</script>
@endsection