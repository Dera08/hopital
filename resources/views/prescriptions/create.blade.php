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

            <form action="{{ route('prescriptions.store') }}" method="POST" class="p-10" x-data="{ category: 'pharmacy' }">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <div class="space-y-8">
                    <div class="p-6 rounded-[2.5rem] border-2 transition-all duration-500"
                         :class="category === 'nurse' ? 'bg-indigo-50 border-indigo-200' : 'bg-blue-50 border-blue-100'">
                        <label class="block text-xs font-black uppercase tracking-widest mb-4"
                               :class="category === 'nurse' ? 'text-indigo-600' : 'text-blue-600'">Cible de la Prescription</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 bg-white rounded-2xl border-2 cursor-pointer transition-all group"
                                   :class="category === 'pharmacy' ? 'border-blue-500 ring-4 ring-blue-500/10' : 'border-gray-100'">
                                <input type="radio" name="category" value="pharmacy" x-model="category" class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <div class="ml-4">
                                    <p class="text-sm font-black text-gray-800 uppercase tracking-tight">Ordonnance</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Pharmacie / Patient</p>
                                </div>
                            </label>
                            
                            <label class="relative flex items-center p-4 bg-white rounded-2xl border-2 cursor-pointer transition-all group"
                                   :class="category === 'nurse' ? 'border-indigo-500 ring-4 ring-indigo-500/10' : 'border-gray-100'">
                                <input type="radio" name="category" value="nurse" x-model="category" class="w-5 h-5 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                <div class="ml-4">
                                    <p class="text-sm font-black text-gray-800 uppercase tracking-tight">Consigne de Soins</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Pour l'Infirmier</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3"
                               x-text="category === 'nurse' ? 'Instruction de Soin / Tâche' : 'Ordonnance / Médicaments'"></label>
                        <textarea name="medication" rows="5" required
                            class="w-full rounded-2xl border-gray-100 bg-gray-50 focus:ring-4 transition-all p-5 font-medium"
                            :class="category === 'nurse' ? 'focus:ring-indigo-500/10 focus:border-indigo-500' : 'focus:ring-blue-500/10 focus:border-blue-500'"
                            :placeholder="category === 'nurse' ? 'Décrivez la tâche (ex: Pansement à refaire tous les jours...)' : 'Détaillez ici les médicaments et dosages...'"></textarea>
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

                        <div x-show="category === 'pharmacy'">
                            <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3">Durée / Dosage global</label>
                            <input type="text" name="duration" placeholder="Ex: 7 jours" 
                                class="w-full rounded-2xl border-gray-100 bg-gray-50 p-4 font-bold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3"
                               x-text="category === 'nurse' ? 'Remarques importantes pour l\'équipe' : 'Instructions diététiques ou repos'"></label>
                        <textarea name="instructions" rows="3" 
                            class="w-full rounded-2xl border-gray-100 bg-gray-50 p-5 font-medium"
                            placeholder="Instructions complémentaires..."></textarea>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-gray-50 flex justify-end">
                    <button type="submit" 
                        class="text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-sm transition-all transform active:scale-95 shadow-lg"
                        :class="category === 'nurse' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-blue-600 hover:bg-blue-700'">
                        <span x-text="category === 'nurse' ? 'Valider la consigne' : 'Valider la prescription'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection