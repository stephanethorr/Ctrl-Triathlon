<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Portail Laboratoires - Échange de données JSON') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Alertes -->
            @if(session('success'))
                <div class="bg-green-600 border border-green-700 text-white px-4 py-3 rounded relative shadow-md">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-600 border border-red-700 text-white px-4 py-3 rounded relative shadow-md">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Étape 1 -->
            <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Étape 1 : Télécharger les prélèvements</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sélectionner le Laboratoire :</label>
                        <select id="main-select-labo" class="w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white h-10">
                            <option value="">-- Choisir un laboratoire --</option>
                            @foreach($laboratoires as $labo)
                                <option value="{{ $labo->idLabo }}">{{ $labo->nomlabo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col md:flex-row gap-4">
                        <!-- Bouton Export -->
                        <form method="POST" action="{{ route('download.export') }}" class="flex-1" onsubmit="return validerChoixLabo(this);">
                            @csrf
                            <input type="hidden" name="idLabo" value="">
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded transition">
                                Télécharger JSON (Vierge)
                            </button>
                        </form>

                        <!-- Bouton Test -->
                        <form method="POST" action="{{ route('download.test') }}" class="flex-1" onsubmit="return validerChoixLabo(this);">
                            @csrf
                            <input type="hidden" name="idLabo" value="">
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded transition">
                                Module de Test (Simuler Analyses)
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Étape 2 -->
            <div class="bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Étape 2 : Envoyer les résultats</h3>
                <form method="POST" action="{{ route('download.import') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fichier de résultats (.json) :</label>
                        <input type="file" name="fichier_json" accept=".json" class="block w-full text-sm text-gray-900 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gray-200 dark:file:bg-gray-600 file:text-gray-700 dark:file:text-white hover:file:bg-gray-300 transition" required>
                    </div>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded h-10 transition">
                        Importer les résultats
                    </button>
                </form>
            </div>
            
        </div>
    </div>

    <!-- Petit script pour lier la liste déroulante aux boutons -->
    <script>
        function validerChoixLabo(form) {
            // On récupère la valeur de la liste déroulante
            let laboSelectionne = document.getElementById('main-select-labo').value;
            
            // Si rien n'est sélectionné, on avertit l'utilisateur et on bloque l'envoi
            if (laboSelectionne === "") {
                alert("Veuillez d'abord sélectionner un laboratoire dans la liste déroulante.");
                return false;
            }
            
            // Si c'est bon, on met l'ID dans le formulaire caché et on laisse passer
            form.idLabo.value = laboSelectionne;
            return true;
        }
    </script>
</x-app-layout>