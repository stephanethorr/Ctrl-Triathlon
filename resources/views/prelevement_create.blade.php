<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Créer un Dossier de Prélèvement (Manuel)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b border-gray-200 dark:border-gray-700 pb-2">
                    Nouveau dossier d'analyse
                </h3>

                <form method="POST" action="{{ route('prelevements.store') }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Triathlète contrôlé :</label>
                            <select name="numLicence" required class="w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white h-10 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Sélectionner un athlète --</option>
                                @foreach($triathletes as $athlete)
                                    <option value="{{ $athlete->numLicence }}">{{ $athlete->nom }} {{ $athlete->prenom }} (Licence: {{ $athlete->numLicence }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sélection du Labo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Laboratoire assigné :</label>
                            <select name="idLabo" required class="w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white h-10 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">-- Sélectionner un laboratoire --</option>
                                @foreach($labos as $labo)
                                    <option value="{{ $labo->idLabo }}">{{ $labo->nomlabo }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-8 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('prelevements') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded transition">
                            Annuler
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition shadow-md">
                            Créer le dossier
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>