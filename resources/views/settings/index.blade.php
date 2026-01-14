@extends('layouts.app')

@section('title', 'Paramètres')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <h1 class="ml-2 text-2xl font-medium text-gray-900">Paramètres</h1>
                </div>

                <p class="mt-6 text-gray-500 leading-relaxed">
                    Gérez vos paramètres de compte et vos préférences de sécurité.
                </p>
            </div>

            <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
                <!-- Sécurité -->
                <div>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <h2 class="ml-3 text-xl font-semibold text-gray-900">Sécurité</h2>
                    </div>

                    <div class="mt-6 space-y-4">
                        <!-- Authentification Multi-Facteurs -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Authentification Multi-Facteurs</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Ajoutez une couche de sécurité supplémentaire à votre compte.
                                    </p>
                                </div>
                                <div class="flex items-center">
                                    @if(auth()->user()->mfa_enabled)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Activé
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Désactivé
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                @if(auth()->user()->mfa_enabled)
                                    <form method="POST" action="{{ route('mfa.disable') }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            Désactiver MFA
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('mfa.setup') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Configurer MFA
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Changer le mot de passe -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Mot de passe</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Mettez à jour votre mot de passe pour maintenir la sécurité de votre compte.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('password.confirm') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Changer le mot de passe
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Préférences -->
                <div>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg>
                        <h2 class="ml-3 text-xl font-semibold text-gray-900">Préférences</h2>
                    </div>

                    <div class="mt-6 space-y-4">
                        <!-- Profil -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Informations du profil</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Mettez à jour vos informations personnelles et de contact.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Modifier le profil
                                </a>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Gérez vos préférences de notifications.
                                    </p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Configurer les notifications
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
