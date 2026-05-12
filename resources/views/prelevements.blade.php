<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Liste des prélèvements') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alertes -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif

            <!-- Zone de contrôle (Filtres et Génération) -->
            <div class="mb-6 bg-white dark:bg-gray-800 p-6 shadow sm:rounded-lg">
                <form id="action-form">
                    @csrf
                    
                    <!-- Ligne 1 : Les 3 filtres -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recherche par laboratoire :</label>
                            <select name="idLabo" class="w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Tous (ou Sélectionnez pour générer)</option>
                                @foreach($labos as $labo)
                                    <option value="{{ $labo->idLabo }}" {{ request('idLabo') == $labo->idLabo ? 'selected' : '' }}>{{ $labo->nomlabo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recherche par triathlon :</label>
                            <select name="idT" class="w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Tous (ou Sélectionnez pour générer)</option>
                                @foreach($triathlons as $t)
                                    <option value="{{ $t->idT }}" {{ request('idT') == $t->idT ? 'selected' : '' }}>{{ $t->nomT }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recherche par triathlète :</label>
                            <select name="numLicence" class="w-full rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Tous</option>
                                @foreach($triathletes as $athlete)
                                    <option value="{{ $athlete->numLicence }}" {{ request('numLicence') == $athlete->numLicence ? 'selected' : '' }}>{{ $athlete->nom }} {{ $athlete->prenom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Ligne 2 : Les actions -->
                    <div class="flex flex-col md:flex-row justify-between items-center border-t border-gray-200 dark:border-gray-700 pt-4 gap-4">
                        
                        <!-- Actions de gauche -->
                        <div class="flex gap-2 w-full md:w-auto">
                            <button type="button" onclick="submitFilter()" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded transition">
                                Actualiser
                            </button>
                            <a href="{{ route('prelevements.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded transition text-center">
                                + Nouveau manuel
                            </a>
                        </div>

                        <!-- Actions de droite -->
                        <div class="flex items-center gap-3 w-full md:w-auto bg-gray-50 dark:bg-gray-900 p-2 rounded border border-gray-200 dark:border-gray-700">
                            <label class="whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300">Taux de prélèvement :</label>
                            <input type="number" name="taux" value="10" min="1" max="100" class="w-20 rounded border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-center">
                            <span class="text-gray-700 dark:text-gray-300 font-bold">%</span>
                            <button type="button" onclick="submitGenerate()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition whitespace-nowrap">
                                Générer les prélèvements pour ce triathlon
                            </button>
                        </div>

                    </div>
                </form>
            </div>

            <!-- Tableau -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-gray-900 dark:text-gray-100 border-collapse">
                        <thead>
                            <tr class="bg-gray-200 dark:bg-gray-700">
                                <th class="border border-gray-300 dark:border-gray-600 p-3">Triathlon</th>
                                <th class="border border-gray-300 dark:border-gray-600 p-3 text-center">Licence</th>
                                <th class="border border-gray-300 dark:border-gray-600 p-3 text-center">Dossard</th>
                                <th class="border border-gray-300 dark:border-gray-600 p-3">Nom</th>
                                <th class="border border-gray-300 dark:border-gray-600 p-3">Prenom</th>
                                <th class="border border-gray-300 dark:border-gray-600 p-3 text-center">Id</th>
                                <th class="border border-gray-300 dark:border-gray-600 p-3 text-center">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prelevements as $prelevement)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <td class="border border-gray-300 dark:border-gray-600 p-3">{{ $prelevement->triathlon_nom }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-3 text-center">{{ $prelevement->numLicence }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-3 text-center">{{ $prelevement->numDossard }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-3">{{ $prelevement->nom }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 p-3">{{ $prelevement->prenom }}</td>
                                <td class="border border-gray-300 dark:border-gray-600 font-bold text-center p-0
                                    @if($prelevement->couleurEtat == 'vert') bg-green-200 text-green-900 hover:bg-green-300
                                    @elseif($prelevement->couleurEtat == 'rouge') bg-red-200 text-red-900 hover:bg-red-300
                                    @else bg-gray-50 text-gray-900 hover:bg-gray-200 dark:bg-gray-900 dark:text-white dark:hover:bg-gray-800 @endif
                                ">
                                    <a href="{{ route('prelevements.show', $prelevement->idPrelevement) }}" class="block w-full h-full p-3 underline">
                                        {{ $prelevement->idPrelevement }}
                                    </a>
                                </td>
                                <td class="border border-gray-300 dark:border-gray-600 p-3 text-center">{{ \Carbon\Carbon::parse($prelevement->datePrelevement)->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="border border-gray-300 dark:border-gray-600 p-6 text-center text-gray-500 dark:text-gray-400 italic">
                                    Aucun prélèvement trouvé.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts pour router le formulaire -->
    <script>
        function submitFilter() {
            let form = document.getElementById('action-form');
            form.method = 'GET';
            form.action = '{{ route('prelevements') }}';
            form.submit();
        }

        function submitGenerate() {
            let form = document.getElementById('action-form');
            form.method = 'POST';
            form.action = '{{ route('prelevements.generer') }}';
            form.submit();
        }
    </script>
</x-app-layout>