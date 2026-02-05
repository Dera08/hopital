@extends('layouts.app')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Configuration Service</h1>
                <p class="text-slate-500 font-medium">Définissez l'identité visuelle et opérationnelle du nouveau pôle</p>
            </div>
            <a href="{{ route('services.index') }}" class="px-6 py-3 bg-white text-slate-600 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm">
                Annuler
            </a>
        </div>

        <div class="bg-white p-10 rounded-[3rem] shadow-2xl shadow-slate-200 border border-slate-100">
            <form method="POST" action="{{ route('services.store') }}" id="serviceForm">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Section Identité -->
                    <div class="space-y-8">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Nom du Service</label>
                            <input type="text" name="name" value="{{ old('name') }}" required placeholder="ex: Cardiologie"
                                class="w-full px-6 py-4 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 transition-all outline-none font-bold text-slate-900 placeholder:text-slate-300">
                            @error('name') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Code Unique</label>
                            <input type="text" name="code" value="{{ old('code') }}" required placeholder="ex: CARDIO"
                                class="w-full px-6 py-4 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 transition-all outline-none font-black text-slate-900 placeholder:text-slate-300 uppercase">
                            @error('code') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Type de Pôle</label>
                            <select name="type" required
                                class="w-full px-6 py-4 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 transition-all outline-none bg-white font-bold text-slate-900 appearance-none cursor-pointer">
                                <option value="medical" {{ old('type') == 'medical' ? 'selected' : '' }}>Pôle de Soins (Médical)</option>
                                <option value="technical" {{ old('type') == 'technical' ? 'selected' : '' }}>Pôle Technique (Diagnostic / Labo)</option>
                                <option value="support" {{ old('type') == 'support' ? 'selected' : '' }}>Pôle de Caisse (Support)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Section Visuelle -->
                    <div class="space-y-8">
                        <div class="flex gap-6">
                            <div class="flex-grow">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Icône (BI Class)</label>
                                <div class="relative">
                                    <input type="text" name="icon" id="iconInput" value="{{ old('icon', 'bi-hospital') }}" required
                                        class="w-full px-6 py-4 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 transition-all outline-none font-bold text-slate-900">
                                    <div id="iconPreview" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 border border-slate-200">
                                        <i class="bi {{ old('icon', 'bi-hospital') }}"></i>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Couleur</label>
                                <input type="color" name="color" value="{{ old('color', '#3b82f6') }}" 
                                    class="w-20 h-[60px] rounded-2xl border border-slate-200 cursor-pointer p-1 bg-white">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Localisation (Pavillon / Étage)</label>
                            <input type="text" name="location" value="{{ old('location') }}" placeholder="ex: Bâtiment A, Étage 1"
                                class="w-full px-6 py-4 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 transition-all outline-none font-bold text-slate-900 placeholder:text-slate-300">
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Prix de Consultation (F CFA)</label>
                            <input type="number" name="consultation_price" value="{{ old('consultation_price', 0) }}" required
                                class="w-full px-6 py-4 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 transition-all outline-none font-black text-slate-900 text-2xl">
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Missions & Description</label>
                        <textarea name="description" rows="3" placeholder="Décrivez brièvement les missions de ce service..."
                            class="w-full px-6 py-4 rounded-2xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-50/50 transition-all outline-none font-medium text-slate-600 leading-relaxed">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-12 pt-10 border-t border-slate-100">
                    <button type="submit" class="bg-blue-600 text-white px-12 py-5 rounded-[2rem] font-black text-lg shadow-2xl shadow-blue-200 hover:bg-blue-700 hover:scale-105 active:scale-95 transition-all flex items-center gap-4">
                        ENREGISTRER LE SERVICE <i class="bi bi-arrow-right-circle-fill"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Guide icônes -->
        <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4 opacity-50">
            <div class="flex items-center gap-2 text-[10px] font-black text-slate-500"><i class="bi bi-heart-pulse"></i> bi-heart-pulse</div>
            <div class="flex items-center gap-2 text-[10px] font-black text-slate-500"><i class="bi bi-baby"></i> bi-baby</div>
            <div class="flex items-center gap-2 text-[10px] font-black text-slate-500"><i class="bi bi-gender-female"></i> bi-gender-female</div>
            <div class="flex items-center gap-2 text-[10px] font-black text-slate-500"><i class="bi bi-microscope"></i> bi-microscope</div>
        </div>
    </div>
</div>

<script>
    document.getElementById('iconInput').addEventListener('input', function(e) {
        const icon = e.target.value;
        const preview = document.querySelector('#iconPreview i');
        preview.className = 'bi ' + icon;
    });
</script>
@endsection