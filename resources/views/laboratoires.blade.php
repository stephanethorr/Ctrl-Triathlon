<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestion des Laboratoires') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alertes de succès ou d'erreur -->
            @if(session('success'))
                <div class="bg-green-600 border border-green-700 text-white px-4 py-3 rounded relative shadow-md mb-4" role="alert">
                    <strong class="font-bold">Succès !</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-600 border border-red-700 text-white px-4 py-3 rounded relative shadow-md mb-4" role="alert">
                    <strong class="font-bold">Erreur !</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Formulaire d'ajout -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700">
                    <form method="POST" action="{{ route('laboratoires.store') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                        @csrf
                        <div class="col-span-1 md:col-span-2">
                            <label for="nomlabo" class="block font-medium text-sm mb-1">Nom du labo :</label>
                            <input type="text" name="nomlabo" id="nomlabo" required class="w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 shadow-sm focus:ring-indigo-500">
                        </div>
                        
                        <div class="col-span-1">
                            <label for="adresse" class="block font-medium text-sm mb-1">Adresse :</label>
                            <input type="text" name="adresse" id="adresse" class="w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 shadow-sm focus:ring-indigo-500">
                        </div>

                        <div class="col-span-1 flex gap-2">
                            <div class="w-1/3">
                                <label for="cp" class="block font-medium text-sm mb-1">CP :</label>
                                <input type="text" name="cp" id="cp" class="w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 shadow-sm focus:ring-indigo-500">
                            </div>
                            <div class="w-2/3">
                                <label for="ville" class="block font-medium text-sm mb-1">Ville :</label>
                                <input type="text" name="ville" id="ville" class="w-full rounded border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-300 shadow-sm focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="col-span-1">
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition ease-in-out duration-150 h-10">
                                Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Liste des laboratoires -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-900">
                                <th class="p-4 border-b dark:border-gray-700 font-semibold">ID</th>
                                <th class="p-4 border-b dark:border-gray-700 font-semibold">Nom du Laboratoire</th>
                                <th class="p-4 border-b dark:border-gray-700 font-semibold">Adresse</th>
                                <th class="p-4 border-b dark:border-gray-700 font-semibold text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($laboratoires as $labo)
                            <!-- La classe "group" ici permet de gérer la couleur du texte au survol -->
                            <tr class="group hover:bg-gray-200 dark:hover:bg-gray-700 transition duration-150">
                                <td class="p-4 border-b dark:border-gray-700 text-gray-900 dark:text-gray-300 group-hover:text-black dark:group-hover:text-white">
                                    {{ $labo->idLabo }}
                                </td>
                                <td class="p-4 border-b dark:border-gray-700 font-bold text-gray-900 dark:text-gray-100 group-hover:text-black dark:group-hover:text-white">
                                    {{ $labo->nomlabo }}
                                </td>
                                <td class="p-4 border-b dark:border-gray-700 text-sm text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200">
                                    <!-- Le ?? '' empêche les plantages si la colonne n'existe pas en base -->
                                    {{ $labo->adresse ?? '' }} <br>
                                    {{ $labo->cp ?? '' }} {{ $labo->ville ?? '' }}
                                </td>
                                <td class="p-4 border-b dark:border-gray-700 text-center">
                                    <form method="POST" action="{{ route('laboratoires.destroy', $labo->idLabo) }}" onsubmit="return confirm('Voulez-vous vraiment supprimer ce laboratoire ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-bold px-2 py-1">
                                            SUPPRIMER
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>