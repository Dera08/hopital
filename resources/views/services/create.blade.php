@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen animate-fade-in">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Ajouter un nouveau Service</h1>
            <p class="text-gray-600">Configuration des départements de {{ auth()->user()->hospital->name }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="bg-white text-gray-700 px-4 py-2 rounded-xl border border-gray-200 hover:bg-gray-50 transition shadow-sm">
            Retour
        </a>
    </div>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white p-8 rounded-xl shadow-lg border border-gray-100">
            <form method="POST" action="{{ route('services.store') }}">
                @csrf

                <div class="mb-6">
                    <label for="name" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Nom du Service</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                    @error('name') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="code" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Code (ex: CARDIO, RAD)</label>
                    <input id="code" type="text" name="code" value="{{ old('code') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">
                    @error('code') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 uppercase mb-2">Description</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition outline-none">{{ old('description') }}</textarea>
                    @error('description') <p class="text-red-500 text-xs mt-2">{{ $message }}</p> @enderror
                </div>



                <div class="flex items-center justify-end mt-8">
                    <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-green-200 transition-all active:scale-95">
                        {{ __('Enregistrer le Service') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection