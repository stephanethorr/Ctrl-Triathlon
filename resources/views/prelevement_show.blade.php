<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dossier de Prélèvement #') }}{{ $prelevement->idPrelevement }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Bloc Infos Principales -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start border-b border-gray-100 dark:border-gray-700 pb-4 mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight">Informations Générales</h3>
                            <p class="text-sm text-gray-500">Détails de l'athlète et de la compétition</p>
                        </div>
                        <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest
                            {{ $etat == 'POSITIF' ? 'bg-red-100 text-red-700 border border-red-200' : ($etat == 'NÉGATIF' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-blue-50 text-blue-700 border border-blue-100') }}">
                            {{ $etat }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Colonne 1 -->
                        <div class="space-y-4">
                            <div>
                                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Laboratoire en charge</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $labos->firstWhere('idLabo', $prelevement->idLabo)->nomlabo ?? 'N/A' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Compétition (Triathlon)</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $triathlons->firstWhere('idT', $prelevement->idT)->nomT ?? 'N/A' }}
                                </dd>
                            </div>
                        </div>

                        <!-- Colonne 2 -->
                        <div class="space-y-4">
                            <div>
                                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Athlète</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $triathletes->firstWhere('numLicence', $prelevement->numLicence)->nom ?? '' }} 
                                    {{ $triathletes->firstWhere('numLicence', $prelevement->numLicence)->prenom ?? '' }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Date du contrôle</dt>
                                <dd class="mt-1 text-sm font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($prelevement->datePrelevement)->format('d/m/Y') }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pied de carte avec actions -->
                <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 flex justify-between items-center border-t border-gray-100 dark:border-gray-700">
                    <a href="{{ route('prelevements') }}" class="text-sm font-bold text-gray-500 hover:text-gray-700 transition">
                        ← Retour à la liste
                    </a>
                    <form method="POST" action="{{ route('prelevements.destroy', $prelevement->idPrelevement) }}" onsubmit="return confirm('Supprimer définitivement ce dossier ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 uppercase tracking-tighter transition">
                            Supprimer le prélèvement
                        </button>
                    </form>
                </div>
            </div>

            <!-- Bloc Analyses -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest">Analyses de Laboratoire</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase">Substance</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase text-center">Mesure</th>
                                <th class="px-6 py-3 text-[10px] font-bold text-gray-400 uppercase text-center">Seuil</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($produits as $prod)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $prod->libelleProduit }}</div>
                                    <div class="text-[10px] text-gray-400 font-mono">CODE: {{ $prod->codeProduit }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($prod->mesure !== null)
                                        <span class="text-sm font-black {{ $prod->mesure > $prod->tauxMaxi ? 'text-red-500' : 'text-green-500' }}">
                                            {{ $prod->mesure }}
                                        </span>
                                    @else
                                        <span class="text-[10px] text-gray-400 italic">En attente</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-xs font-medium text-gray-500">
                                    {{ $prod->tauxMaxi }}
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