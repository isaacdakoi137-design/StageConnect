<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Suivi des Stages de Recrutement</h1>
            <p class="text-gray-500 mt-2">Gérez les conventions de stage, carnets de bord hebdomadaires, et rapports de soutenance académiques.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 p-4 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            @forelse($stages as $stage)
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="space-y-3 flex-grow">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold uppercase px-2.5 py-1 rounded-lg 
                                @if($stage->status === 'En cours') bg-indigo-50 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300
                                @elseif($stage->status === 'Convention validée') bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300
                                @elseif($stage->status === 'Soutenance programmée') bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300
                                @else bg-gray-50 text-gray-700 dark:bg-gray-900 dark:text-gray-300
                                @endif">
                                {{ $stage->status }}
                            </span>
                            
                            @if($stage->final_grade)
                                <span class="text-xs font-bold bg-teal-50 text-teal-700 dark:bg-teal-950 dark:text-teal-300 px-2.5 py-1 rounded-lg">
                                    Évalué : {{ $stage->final_grade }}/20
                                </span>
                            @endif
                        </div>

                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $stage->offer->title }}</h2>
                            <p class="text-sm text-gray-500 mt-0.5">
                                Chez <strong>{{ $stage->offer->company }}</strong> | Candidat : {{ $stage->student->name ?? 'N/A' }}
                            </p>
                        </div>

                        <!-- Date grid -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                            <div>
                                <p class="text-gray-400">Date de début</p>
                                <p class="font-bold text-gray-700 dark:text-gray-300 mt-0.5">
                                    {{ $stage->start_date ? \Carbon\Carbon::parse($stage->start_date)->format('d/m/Y') : 'Non définie' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400">Date de fin</p>
                                <p class="font-bold text-gray-700 dark:text-gray-300 mt-0.5">
                                    {{ $stage->end_date ? \Carbon\Carbon::parse($stage->end_date)->format('d/m/Y') : 'Non définie' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400">Encadreur assigné</p>
                                <p class="font-bold text-gray-700 dark:text-gray-300 mt-0.5">
                                    {{ $stage->supervisor->name ?? 'En attente' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-400">Rapport de stage</p>
                                <p class="font-bold text-gray-700 dark:text-gray-300 mt-0.5">
                                    @if($stage->report)
                                        <span class="text-emerald-600 dark:text-emerald-400">Déposé</span>
                                    @else
                                        <span class="text-gray-400">Non déposé</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center md:justify-end shrink-0 gap-2">
                        <a href="{{ route('stages.show', $stage) }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow transition-all">
                            Gérer / Voir détails
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-3xl p-8 text-center text-gray-400 italic">
                    Aucun stage déclaré pour le moment dans vos registres.
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
