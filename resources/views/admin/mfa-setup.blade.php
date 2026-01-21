@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen animate-fade-in">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Configuration MFA</h1>
            <p class="text-gray-600 text-lg">{{ auth()->user()->hospital->name ?? 'Hôpital' }}</p>
            <p class="text-sm text-gray-500 mt-1">Sécurisez votre compte administrateur avec l'authentification multi-facteurs</p>
        </div>
        <div class="flex space-x-3">
            <span class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg">
                {{ now()->format('F Y') }}
            </span>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Authentification Multi-Facteurs
                </h3>
            </div>
            <div class="p-8">
                <div class="mb-8">
                    <h4 class="text-xl font-semibold text-gray-800 mb-4">Renforcez la sécurité de votre compte</h4>
                    <p class="text-gray-600 leading-relaxed">
                        L'authentification multi-facteurs (MFA) ajoute une couche de protection supplémentaire à votre compte administrateur.
                        En plus de votre mot de passe, vous devrez saisir un code unique généré par une application d'authentification
                        à chaque connexion, rendant votre compte beaucoup plus difficile à compromettre.
                    </p>
                </div>

                <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-xl p-6 mb-8">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Applications recommandées</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                    Google Authenticator
                                </div>
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                    Microsoft Authenticator
                                </div>
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                    Authy
                                </div>
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-3"></span>
                                    1Password
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!$user->mfa_enabled)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-semibold text-yellow-800">MFA non activé</h4>
                                <p class="text-sm text-yellow-700 mt-1">
                                    Votre compte n'est protégé que par un mot de passe. Activez le MFA pour une sécurité optimale.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="post" action="{{ route('mfa.setup.post') }}" class="text-center">
                        @csrf

                        <div class="mb-6">
                            <p class="text-gray-600 mb-4">
                                Cliquez sur le bouton ci-dessous pour activer l'authentification multi-facteurs.
                                Un secret unique sera généré et associé à votre compte.
                            </p>
                        </div>

                        <button type="submit" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-8 py-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center mx-auto">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Activer l'authentification multi-facteurs
                        </button>
                    </form>
                @else
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-8">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-sm font-semibold text-green-800">MFA activé</h4>
                                <p class="text-sm text-green-700 mt-1">
                                    Votre compte est protégé par l'authentification multi-facteurs.
                                    Vous devez saisir un code d'authentification à chaque connexion.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="text-gray-600 mb-4">
                            Si vous souhaitez désactiver le MFA, cliquez sur le bouton ci-dessous.
                            Notez que cela réduira la sécurité de votre compte.
                        </p>

                        <form method="post" action="{{ route('mfa.disable') }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-3 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Désactiver MFA
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.animate-fade-in {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
@endsection