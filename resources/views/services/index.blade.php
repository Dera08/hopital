@extends('layouts.app')

@section('content')
<div class="p-8 bg-slate-50 min-h-screen">
    <!-- Header Premium -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Architecture Hospitalière</h1>
            <p class="text-slate-500 font-medium flex items-center gap-2">
                <span class="w-8 h-1 bg-blue-600 rounded-full"></span>
                Configuration des pôles d'activité de {{ auth()->user()->hospital->name }}
            </p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-white text-slate-600 font-bold rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all shadow-sm">
                Tableau de Bord
            </a>
            <a href="{{ route('services.create') }}" class="px-6 py-3 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center gap-2">
                <i class="bi bi-plus-lg"></i> NOUVEAU SERVICE
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl mb-8 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <i class="bi bi-check-circle-fill text-xl text-emerald-500"></i>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
            <button class="text-emerald-500 hover:text-emerald-700 font-black">OK</button>
        </div>
    @endif

    <!-- Statistiques des Pôles -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-blue-100">
                    <i class="bi bi-heart-pulse"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900">{{ $services->where('type', 'medical')->count() }}</div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pôles de Soins</div>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-blue-600 h-full rounded-full" style="width: 100%"></div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-purple-100">
                    <i class="bi bi-microscope"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900">{{ $services->where('type', 'technical')->count() }}</div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pôles Techniques</div>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-purple-600 h-full rounded-full" style="width: 100%"></div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-orange-100">
                    <i class="bi bi-cash-register"></i>
                </div>
                <div>
                    <div class="text-2xl font-black text-slate-900">{{ $services->where('type', 'support')->count() }}</div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pôles de Caisse</div>
                </div>
            </div>
            <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-orange-600 h-full rounded-full" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <!-- Filtres rapides -->
    <div class="flex flex-wrap gap-4 mb-8">
        <button class="px-6 py-2 bg-slate-900 text-white rounded-xl text-xs font-black tracking-widest uppercase">Tous les services</button>
        <button class="px-6 py-2 bg-white text-slate-400 hover:bg-blue-50 hover:text-blue-600 border border-slate-200 rounded-xl text-xs font-black tracking-widest uppercase transition-all">Médical</button>
        <button class="px-6 py-2 bg-white text-slate-400 hover:bg-purple-50 hover:text-purple-600 border border-slate-200 rounded-xl text-xs font-black tracking-widest uppercase transition-all">Technique</button>
        <button class="px-6 py-2 bg-white text-slate-400 hover:bg-orange-50 hover:text-orange-600 border border-slate-200 rounded-xl text-xs font-black tracking-widest uppercase transition-all">Support</button>
    </div>

    <!-- Grille de Services Premium -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($services as $service)
            <div class="group bg-white rounded-[2.5rem] border border-slate-200 p-8 hover:border-blue-400 hover:shadow-2xl hover:shadow-blue-100 transition-all duration-500 relative overflow-hidden flex flex-col h-full">
                <!-- Décoration de fond (Cercle de couleur) -->
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-slate-50 rounded-full group-hover:bg-opacity-100 transition-all duration-500" style="background-color: {{ $service->color }}08"></div>
                
                <!-- Icone et Badge Type -->
                <div class="flex justify-between items-start mb-8 relative z-10">
                    <div class="w-16 h-16 rounded-[1.5rem] flex items-center justify-center text-3xl shadow-sm border" style="background-color: {{ $service->color }}15; color: {{ $service->color }}; border-color: {{ $service->color }}30">
                        <i class="bi {{ $service->icon }}"></i>
                    </div>
                    <div>
                        @if($service->type === 'support')
                            <span class="px-3 py-1 bg-orange-50 text-orange-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-orange-100">SUPPORT</span>
                        @elseif($service->type === 'technical')
                            <span class="px-3 py-1 bg-purple-50 text-purple-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-purple-100">TECHNIQUE</span>
                        @else
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-blue-100">MÉDICAL</span>
                        @endif
                    </div>
                </div>

                <!-- Info Service -->
                <div class="relative z-10 flex-grow">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-[10px] font-black text-slate-400 tracking-tighter uppercase">{{ $service->code }}</span>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 group-hover:text-blue-600 transition-colors mb-3">{{ $service->name }}</h3>
                    <p class="text-slate-500 text-sm font-medium line-clamp-2 leading-relaxed mb-4">{{ $service->description ?? 'Aucune description disponible pour ce service.' }}</p>
                    
                    <div class="flex items-center gap-2 text-slate-400 mb-6">
                        <i class="bi bi-geo-alt-fill text-xs"></i>
                        <span class="text-[11px] font-bold uppercase tracking-tight">{{ $service->location ?? 'Non localisé' }}</span>
                    </div>
                </div>

                <!-- Footer Card -->
                <div class="pt-6 border-t border-slate-100 flex items-center justify-between mt-auto relative z-10">
                    <div>
                        <div class="text-lg font-black text-slate-900">{{ number_format($service->consultation_price, 0, ',', ' ') }} <small class="text-[10px] text-slate-400">F CFA</small></div>
                        <div class="text-[9px] font-black text-slate-400 tracking-widest uppercase">Prix Consultation</div>
                    </div>
                    <div class="flex gap-2">
                        <a href="#" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-blue-50 hover:text-blue-600 flex items-center justify-center transition-all border border-transparent hover:border-blue-100 shadow-sm">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                    </div>
                </div>

                <!-- Status Dot -->
                <div class="absolute top-6 left-6">
                    <div class="w-3 h-3 rounded-full {{ $service->is_active ? 'bg-emerald-500 shadow-lg shadow-emerald-200' : 'bg-slate-300' }}"></div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                <div class="text-6xl text-slate-200 mb-4 font-black">VIDE</div>
                <p class="text-slate-500 font-bold mb-8">Aucun service n'a encore été configuré dans cet hôpital.</p>
                <a href="{{ route('services.create') }}" class="px-8 py-4 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-100">
                    CRÉER LE PREMIER SERVICE
                </a>
            </div>
        @endforelse
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .grid > div {
        animation: fade-in 0.5s ease-out forwards;
    }
</style>
@endsection
