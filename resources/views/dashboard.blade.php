<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Message de bienvenue standard -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Vous êtes connecté !") }}
                </div>
            </div>

            <!-- Espace réservé aux Laboratoires (role_id == 2) -->
            @if(Auth::check() && Auth::user()->role_id == 2)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-8 border-purple-500">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Espace Laboratoire
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Portail de téléchargement des lots à analyser et télétransmission des résultats.
                </p>
                <div class="grid grid-cols-1 gap-6">
                    
                    <!-- CORRECTION ICI : Le lien pointe maintenant vers 'download.index' comme écrit dans ton web.php -->
                    <a href="{{ route('download.index') }}" class="p-6 bg-purple-500 hover:bg-purple-600 text-white rounded-lg text-center font-bold flex flex-col items-center justify-center transition duration-150">
                        <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        <span>Accéder au Portail Laboratoire (JSON)</span>
                    </a>

                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>