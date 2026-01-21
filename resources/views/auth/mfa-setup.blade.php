<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Configuration de l\'Authentification Multi-Facteurs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Sécurisez votre compte</h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            L'authentification multi-facteurs ajoute une couche de sécurité supplémentaire à votre compte.
                            Vous devrez saisir un code généré par une application d'authentification en plus de votre mot de passe.
                        </p>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                                    Applications recommandées
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Google Authenticator</li>
                                        <li>Microsoft Authenticator</li>
                                        <li>Authy</li>
                                        <li>1Password</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="post" action="{{ route('mfa.setup.post') }}">
                        @csrf

                        <div class="mb-6">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                Cliquez sur le bouton ci-dessous pour activer l'authentification multi-facteurs pour votre compte.
                                Un secret sera généré et stocké de manière sécurisée.
                            </p>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                <p>Une fois activé, vous devrez saisir un code à chaque connexion.</p>
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-150 ease-in-out">
                                Activer MFA
                            </button>
                        </div>
                    </form>

                    @if($user->mfa_enabled)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                            MFA déjà activé
                                        </h3>
                                        <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                            <p>L'authentification multi-facteurs est déjà activée pour votre compte.</p>
                                        </div>
                                        <div class="mt-4">
                                            <form method="post" action="{{ route('mfa.disable') }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium py-2 px-3 rounded transition duration-150 ease-in-out">
                                                    Désactiver MFA
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>