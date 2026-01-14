 @extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f8fafc] py-12">
    <div class="max-w-4xl mx-auto px-6">
        <a href="{{ route('medecin.dashboard') }}" class="inline-flex items-center text-sm font-bold text-blue-600 mb-8 hover:text-blue-800">
            ← Retour au tableau de bord
        </a>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-blue-900/5 border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-10 py-8 text-white">
                <h2 class="text-3xl font-black uppercase tracking-tight">Nouvelle Prescription</h2>
                <p class="text-blue-100 font-medium mt-1">Patient : <span class="font-bold text-white uppercase">{{ $patient->full_name }}</span> (IPU: {{ $patient->ipu }})</p>
            </div>

            <form action="{{ route('prescriptions.store') }}" method="POST" class="p-10">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <div class="space-y-8">
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3">Ordonnance / Médicaments</label>
                        <textarea name="medication" rows="5" required
                            class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all p-5 font-medium"
                            placeholder="Détaillez ici les médicaments et dosages..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3">Type de traitement</label>
                            <select name="type" class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 font-bold text-gray-700">
                                <option value="curatif">Curatif</option>
                                <option value="preventif">Préventif</option>
                                <option value="symptomatique">Symptomatique</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3">Durée / Dosage global</label>
                            <input type="text" name="duration" placeholder="Ex: 7 jours" 
                                class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3">Instructions diététiques ou repos</label>
                        <textarea name="instructions" rows="3" 
                            class="w-full rounded-2xl border-gray-100 bg-gray-50 p-5 font-medium"
                            placeholder="Instructions complémentaires..."></textarea>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-gray-50 flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-sm hover:bg-blue-700 transition-all transform active:scale-95">
                        Valider la prescription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection