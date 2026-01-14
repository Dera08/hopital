@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen animate-fade-in">
    <div class="flex justify-between items-center mb-8">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold text-gray-800">{{ $prestation->name }}</h1>
                @if($prestation->is_pack)
                    <span class="bg-orange-100 text-orange-700 text-xs font-black px-2 py-1 rounded-lg">FORFAIT PACK</span>
                @endif
            </div>
            <p class="text-gray-600">ID: #{{ $prestation->code }} | Service: {{ $prestation->service->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('prestations.edit', $prestation) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-bold shadow-lg transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Modifier
            </a>
            <a href="{{ route('prestations.index') }}" class="bg-white text-gray-700 px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition shadow-sm">
                Retour
            </a>
        </div>
    </div>

    <div class="max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Fiche Technique
                </h3>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">Prix de vente</label>
                        <p class="text-2xl font-black text-green-600">{{ number_format($prestation->price, 0, ',', ' ') }} CFA</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">Catégorie</label>
                        <p class="text-gray-900 font-medium">{{ ucfirst($prestation->category) }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">Durée Estimée</label>
                        <p class="text-gray-900">{{ $prestation->estimated_duration ?? '--' }} minutes</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase">Priorité</label>
                        <span class="text-sm font-bold @if($prestation->priority == 'urgent') text-red-600 @else text-blue-600 @endif">
                            {{ ucfirst($prestation->priority) }}
                        </span>
                    </div>
                </div>

                @if($prestation->description)
                <div class="mt-8 pt-6 border-t border-gray-100">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Instructions / Description</label>
                    <p class="text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-lg italic">"{{ $prestation->description }}"</p>
                </div>
                @endif
            </div>

            @if($prestation->is_pack && $prestation->pack)
            <div class="bg-white rounded-xl shadow-lg border border-orange-100 p-8">
                <h3 class="text-lg font-bold text-orange-700 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Composition du Pack Forfaitaire
                </h3>
                <div class="space-y-3">
                    @foreach($prestation->pack->prestations as $sub)
                    <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg border border-orange-100">
                        <span class="font-medium text-gray-800">{{ $sub->name }}</span>
                        <span class="text-xs font-bold bg-white px-2 py-1 rounded shadow-sm">{{ number_format($sub->price) }} CFA</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6">
                <h3 class="text-md font-bold text-gray-800 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    Workflow Médical
                </h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Paiement :</span>
                        <span class="font-bold text-gray-800">
                            {{ $prestation->payment_timing == 'before' ? 'Obligatoire avant' : 'Après réalisation' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Approbation :</span>
                        <span class="font-bold {{ $prestation->requires_approval ? 'text-orange-600' : 'text-green-600' }}">
                            {{ $prestation->requires_approval ? 'Niveau ' . $prestation->approval_level : 'Aucune' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Urgence :</span>
                        <span class="font-bold {{ $prestation->is_emergency ? 'text-red-600' : 'text-gray-800' }}">
                            {{ $prestation->is_emergency ? 'OUI' : 'NON' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-900 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-md font-bold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path></svg>
                    Matériel Requis
                </h3>
                @php $equipments = is_string($prestation->required_equipment) ? json_decode($prestation->required_equipment) : $prestation->required_equipment; @endphp
                @if($equipments && count($equipments) > 0)
                    <ul class="space-y-2">
                        @foreach($equipments as $item)
                            <li class="flex items-center text-sm">
                                <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full mr-2"></span>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-xs text-indigo-300 italic">Aucun équipement spécifique déclaré.</p>
                @endif
            </div>

            <form method="POST" action="{{ route('prestations.destroy', $prestation) }}" 
                  onsubmit="return confirm('Attention : La suppression est irréversible.')" class="w-full">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full text-red-600 hover:bg-red-50 py-2 rounded-lg text-sm font-semibold transition">
                    Supprimer du catalogue
                </button>
            </form>
        </div>
    </div>
</div>
@endsection