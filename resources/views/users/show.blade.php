{{-- resources/views/users/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Détails de l\'utilisateur')

@section('content')
<div class="p-6">
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('users.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour à la liste
            </a>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $user->name }}</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Informations principales -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Profil -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations du profil</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-600 font-bold text-xl">
                                    {{ substr($user->name, 0, 2) }}
                                </span>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Rôle:</span>
                                @php
                                    $roleColors = [
                                        'admin' => 'red',
                                        'doctor' => 'blue',
                                        'nurse' => 'green',
                                        'administrative' => 'purple'
                                    ];
                                    $color = $roleColors[$user->role] ?? 'gray';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 ml-2">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>

                            <div>
                                <span class="text-sm font-medium text-gray-500">Service:</span>
                                <span class="text-sm text-gray-900 ml-2">{{ $user->service->name ?? 'Non assigné' }}</span>
                            </div>

                            <div>
                                <span class="text-sm font-medium text-gray-500">Statut:</span>
                                @if($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                    Actif
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 ml-2">
                                    Inactif
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($user->phone)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Téléphone:</span>
                            <p class="text-sm text-gray-900">{{ $user->phone }}</p>
                        </div>
                        @endif

                        @if($user->registration_number)
                        <div>
                            <span class="text-sm font-medium text-gray-500">N° d'enregistrement:</span>
                            <p class="text-sm text-gray-900">{{ $user->registration_number }}</p>
                        </div>
                        @endif

                        <div>
                            <span class="text-sm font-medium text-gray-500">Créé le:</span>
                            <p class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                        </div>

                        <div>
                            <span class="text-sm font-medium text-gray-500">Dernière modification:</span>
                            <p class="text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Statistiques (pour les médecins) -->
                @if($user->isDoctor() && isset($stats))
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistiques d'activité</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-3">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['appointments'] ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Rendez-vous</div>
                        </div>

                        <div class="text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-3">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ $stats['patients'] ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Patients</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>

                    <div class="space-y-3">
                        <a href="{{ route('users.edit', $user) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Modifier
                        </a>

                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('users.toggle-status', $user) }}" class="w-full">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white font-semibold rounded-lg transition">
                                @if($user->is_active)
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728m0 0L5 21m13.364-15.364L5.636 18.364"></path>
                                </svg>
                                Désactiver
                                @else
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Activer
                                @endif
                            </button>
                        </form>
                        @endif

                        @if(auth()->user()->isAdmin() && $user->mfa_enabled ?? false)
                        <form method="POST" action="{{ route('users.disable-mfa', $user) }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-lg transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Désactiver MFA
                            </button>
                        </form>
                        @endif
                    </div>
                </div>

                <!-- Informations système -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informations système</h2>

                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="font-medium text-gray-500">ID:</span>
                            <span class="text-gray-900 ml-2">#{{ $user->id }}</span>
                        </div>

                        @if($user->email_verified_at)
                        <div>
                            <span class="font-medium text-gray-500">Email vérifié:</span>
                            <span class="text-green-600 ml-2">Oui</span>
                        </div>
                        @else
                        <div>
                            <span class="font-medium text-gray-500">Email vérifié:</span>
                            <span class="text-red-600 ml-2">Non</span>
                        </div>
                        @endif

                        @if($user->mfa_enabled ?? false)
                        <div>
                            <span class="font-medium text-gray-500">Authentification 2FA:</span>
                            <span class="text-green-600 ml-2">Activée</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
