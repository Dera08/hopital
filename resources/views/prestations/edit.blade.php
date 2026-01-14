@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen animate-fade-in">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Modifier la Prestation</h1>
            <p class="text-gray-600">Modification de la prestation médicale de {{ auth()->user()?->hospital?->name ?? 'Hôpital' }}</p>
        </div>
        <a href="{{ route('prestations.index') }}" class="bg-white text-gray-700 px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition shadow-sm">
            Retour
        </a>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
            <form method="POST" action="{{ route('prestations.update', $prestation) }}">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Nom de la Prestation</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $prestation->name) }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                    @error('name') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="code" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Code (ex: CONS-CARDIO, EXAM-RAD)</label>
                    <input id="code" type="text" name="code" value="{{ old('code', $prestation->code) }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                    @error('code') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="category" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Catégorie</label>
                    <select id="category" name="category" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                        <option value="">Sélectionner une catégorie</option>
                        <option value="consultation" {{ old('category', $prestation->category) == 'consultation' ? 'selected' : '' }}>Consultation</option>
                        <option value="examen" {{ old('category', $prestation->category) == 'examen' ? 'selected' : '' }}>Examen</option>
                        <option value="soins" {{ old('category', $prestation->category) == 'soins' ? 'selected' : '' }}>Soins</option>
                        <option value="medicament" {{ old('category', $prestation->category) == 'medicament' ? 'selected' : '' }}>Médicament</option>
                        <option value="hospitalisation" {{ old('category', $prestation->category) == 'hospitalisation' ? 'selected' : '' }}>Hospitalisation</option>
                    </select>
                    @error('category') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="service_id" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Service Associé</label>
                    <select id="service_id" name="service_id" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                        <option value="">Sélectionner un service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id', $prestation->service_id) == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                        @endforeach
                    </select>
                    @error('service_id') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="price" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Prix (CFA)</label>
                    <input id="price" type="number" name="price" value="{{ old('price', $prestation->price) }}" step="1" min="0" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                    @error('price') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Description</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">{{ old('description', $prestation->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="payment_timing" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Moment du Paiement</label>
                        <select id="payment_timing" name="payment_timing" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                            <option value="before" {{ old('payment_timing', $prestation->payment_timing) == 'before' ? 'selected' : '' }}>Avant la prestation</option>
                            <option value="after" {{ old('payment_timing', $prestation->payment_timing) == 'after' ? 'selected' : '' }}>Après la prestation</option>
                            <option value="upon_completion" {{ old('payment_timing', $prestation->payment_timing) == 'upon_completion' ? 'selected' : '' }}>À la fin</option>
                        </select>
                        @error('payment_timing') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="priority" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Priorité</label>
                        <select id="priority" name="priority" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                            <option value="low" {{ old('priority', $prestation->priority) == 'low' ? 'selected' : '' }}>Faible</option>
                            <option value="medium" {{ old('priority', $prestation->priority) == 'medium' ? 'selected' : '' }}>Moyenne</option>
                            <option value="high" {{ old('priority', $prestation->priority) == 'high' ? 'selected' : '' }}>Élevée</option>
                            <option value="urgent" {{ old('priority', $prestation->priority) == 'urgent' ? 'selected' : '' }}>Urgente</option>
                        </select>
                        @error('priority') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $prestation->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Prestation active</span>
                    </label>
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="requires_payment" value="1" {{ old('requires_payment', $prestation->requires_payment) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Paiement requis</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-8">
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-blue-200 transition-all active:scale-95">
                        {{ __('Mettre à jour la Prestation') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
