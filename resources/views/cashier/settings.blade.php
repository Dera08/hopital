@extends('layouts.cashier_layout')

@section('content')
<div class="p-8 bg-gray-50 min-h-screen">
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-800">Paramètres système</h2>
        <p class="text-gray-500">Configurez les informations de l'établissement et les tarifs des actes.</p>
    </div>

    <form action="{{ route('cashier.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Bloc 1: Informations de l'hôpital --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                        <i class="fas fa-hospital text-xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-800 tracking-tight">Informations de l'hôpital</h3>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nom de l'établissement</label>
                        <input type="text" name="hospital_name" value="Centre Hospitalier Universitaire" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Adresse</label>
                        <input type="text" name="address" value="Abidjan, Côte d'Ivoire" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 outline-none">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Téléphone</label>
                            <input type="text" name="phone" value="0700000000" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Email</label>
                            <input type="email" name="email" value="contact@chu.ci" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bloc 2: Paramètres de paiement --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                        <i class="fas fa-coins text-xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-gray-800 tracking-tight">Paramètres de paiement</h3>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Devise</label>
                        <select name="currency" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-bold">
                            <option value="XOF">F CFA (BCEAO)</option>
                            <option value="EUR">Euro (€)</option>
                            <option value="USD">Dollar ($)</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Préfixe Reçus</label>
                            <input type="text" name="receipt_prefix" value="REC" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-center font-black">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Préfixe Factures</label>
                            <input type="text" name="invoice_prefix" value="INV" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-center font-black">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bloc 3: Services et tarifs --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 lg:col-span-2">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-xl font-black text-gray-800">Services et tarifs par défaut</h3>
                    <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-xl text-xs font-black hover:bg-black transition-all">
                        <i class="fas fa-plus mr-2"></i> AJOUTER SERVICE
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left border-b border-gray-100">
                                <th class="pb-4 text-xs font-black text-gray-400 uppercase tracking-widest">Nom du service</th>
                                <th class="pb-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Tarif de base</th>
                                <th class="pb-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            {{-- Boucle sur vos prestations réelles --}}
                            @php
                                $demoServices = [
                                    ['name' => 'Consultation générale', 'price' => 5000],
                                    ['name' => 'Radiologie', 'price' => 15000],
                                    ['name' => 'Analyse de sang', 'price' => 12000]
                                ];
                            @endphp
                            @foreach($demoServices as $service)
                            <tr class="group">
                                <td class="py-4 text-sm font-bold text-gray-700">{{ $service['name'] }}</td>
                                <td class="py-4 text-sm font-black text-gray-900 text-right">{{ number_format($service['price'], 0) }} F</td>
                                <td class="py-4 text-right">
                                    <button type="button" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"><i class="fas fa-edit"></i></button>
                                    <button type="button" class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mt-10 flex justify-end gap-4">
            <button type="reset" class="px-8 py-3 text-sm font-black text-gray-500 hover:text-gray-800 transition-all">ANNULER</button>
            <button type="submit" class="px-10 py-4 bg-blue-600 text-white rounded-2xl font-black shadow-xl shadow-blue-200 hover:bg-blue-700 transition-all">
                ENREGISTRER LES CONFIGURATIONS
            </button>
        </div>
    </form>
</div>
@endsection