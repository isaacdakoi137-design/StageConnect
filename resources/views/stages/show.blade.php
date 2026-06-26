<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-4">
        <!-- Breadcrumb / Header -->
        <div class="mb-8">
            <a href="{{ route('stages.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour aux stages
            </a>
            <h1 class="text-3xl font-extrabold tracking-tight mt-2 text-gray-900 dark:text-white">Détails du Suivi de Stage</h1>
            <p class="text-gray-500 mt-1">Consultez l'évolution, le carnet de bord, et programmez la soutenance académique.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 p-4 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-200 text-red-700 p-4 rounded-xl mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Colonne gauche: Fiche d'informations & Documents -->
            <div class="space-y-6 lg:col-span-1">
                
                <!-- Fiche d'informations -->
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow rounded-2xl p-6 space-y-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white pb-2 border-b">Fiche Descriptive</h2>
                    
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-400">Poste & Entreprise</p>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $stage->offer->title }}</p>
                            <p class="text-gray-500 text-xs">{{ $stage->offer->company }}</p>
                        </div>

                        <div>
                            <p class="text-gray-400">Étudiant Stagiaire</p>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $stage->student->name }}</p>
                            <p class="text-gray-500 text-xs">{{ $stage->student->email }}</p>
                        </div>

                        <div>
                            <p class="text-gray-400">Encadreur Professionnel</p>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $stage->supervisor->name ?? 'En attente d\'attribution' }}</p>
                        </div>

                        <div>
                            <p class="text-gray-400">Statut du Stage</p>
                            <span class="inline-block mt-1 text-xs font-bold px-2 py-1 rounded bg-indigo-50 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300">
                                {{ $stage->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Assignation encadreur (Entreprise uniquement) -->
                    @if(Auth::user()->hasRole('Entreprise') && Auth::id() === $stage->offer->user_id && !$stage->supervisor_id)
                        <div class="border-t pt-4 mt-4">
                            <form action="{{ route('stages.assign-supervisor', $stage) }}" method="POST" class="space-y-3">
                                @csrf
                                <label class="block text-xs font-bold uppercase tracking-wider text-slate-500">Désigner un encadreur</label>
                                <select name="supervisor_id" required class="w-full border rounded-xl p-2 bg-slate-50 text-sm focus:ring-indigo-500">
                                    <option value="">-- Choisir --</option>
                                    @foreach($supervisors as $sv)
                                        <option value="{{ $sv->id }}">{{ $sv->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-2 rounded-xl text-xs font-semibold shadow">
                                    Attribuer
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Validation convention (Ecole uniquement) -->
                    @if(Auth::user()->hasRole('Ecole') && $stage->status === 'En cours')
                        <div class="border-t pt-4 mt-4">
                            <form action="{{ route('stages.validate-convention', $stage) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white py-2.5 rounded-xl text-xs font-semibold shadow transition-all">
                                    Valider la convention
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Documents administratifs -->
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow rounded-2xl p-6 space-y-4">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white pb-2 border-b">Documents du Stage</h2>

                    <div class="space-y-4">
                        <!-- Convention -->
                        <div class="p-3 bg-slate-50 dark:bg-gray-900/50 rounded-xl flex items-center justify-between border border-gray-100 dark:border-gray-800">
                            <div>
                                <h3 class="font-bold text-sm text-gray-900 dark:text-white">Convention de stage</h3>
                                <p class="text-xxs text-gray-400">Générée automatiquement par l'école</p>
                            </div>
                            <span class="text-xs font-bold text-emerald-600">✓ Signée</span>
                        </div>

                        <!-- Rapport final -->
                        <div class="p-3 bg-slate-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-800 space-y-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Rapport final de stage</h3>
                                    <p class="text-xxs text-gray-400">Dépôt PDF par l'étudiant</p>
                                </div>
                                @if($stage->report)
                                    <span class="text-xs font-bold text-emerald-600">✓ Déposé</span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Absent</span>
                                @endif
                            </div>

                            @if($stage->report)
                                <a href="{{ asset('storage/' . $stage->report) }}" target="_blank" class="block w-full text-center border border-indigo-200 text-indigo-600 py-2 rounded-xl text-xs font-bold hover:bg-indigo-50/50">
                                    Visualiser le rapport
                                </a>
                            @endif

                            @if(Auth::id() === $stage->student_id)
                                <form action="{{ route('stages.upload-report', $stage) }}" method="POST" enctype="multipart/form-data" class="pt-2 border-t border-slate-200/50 space-y-2">
                                    @csrf
                                    <label class="block text-xxs font-bold text-slate-500 uppercase">Déposer/Mettre à jour le rapport PDF</label>
                                    <input type="file" name="report" required class="w-full text-xs border rounded p-1.5">
                                    <button type="submit" class="bg-indigo-600 text-white w-full py-1.5 rounded-lg text-xxs font-bold shadow hover:bg-indigo-500">
                                        Téléverser
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite: Carnet de bord (Weekly logs) & Planification Soutenance -->
            <div class="space-y-6 lg:col-span-2">
                
                <!-- Gestion des soutenances (Jury / Date / Note) -->
                @if($stage->report || $stage->defense_date || $stage->final_grade)
                    <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow rounded-2xl p-6 space-y-4">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white pb-2 border-b">Évaluation & Soutenance académique</h2>

                        @if($stage->defense_date)
                            <div class="p-4 bg-indigo-50 dark:bg-indigo-950/40 rounded-xl border border-indigo-100 dark:border-indigo-900 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-xs text-indigo-400 font-semibold uppercase">Date de Soutenance</p>
                                    <p class="font-bold text-indigo-900 dark:text-indigo-300 mt-0.5">{{ \Carbon\Carbon::parse($stage->defense_date)->format('d/m/Y à H:i') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-indigo-400 font-semibold uppercase">Membres du Jury</p>
                                    <p class="font-bold text-indigo-900 dark:text-indigo-300 mt-0.5">{{ $stage->jury_members }}</p>
                                </div>
                            </div>
                        @endif

                        @if(Auth::user()->hasRole('Ecole'))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                <!-- Programmation soutenance -->
                                @if(!$stage->defense_date)
                                    <form action="{{ route('stages.schedule-defense', $stage) }}" method="POST" class="space-y-3 bg-slate-50 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                                        @csrf
                                        <h3 class="font-bold text-sm text-gray-900 dark:text-white">Planifier la soutenance</h3>
                                        
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Date & Heure</label>
                                            <input type="datetime-local" name="defense_date" required class="w-full border rounded-lg p-2 text-xs">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Jury (Membres)</label>
                                            <input type="text" name="jury_members" required placeholder="Ex: M. Martin, Mme. Robert" class="w-full border rounded-lg p-2 text-xs">
                                        </div>

                                        <button type="submit" class="bg-indigo-600 text-white w-full py-2 rounded-lg text-xs font-semibold shadow hover:bg-indigo-500">
                                            Planifier
                                        </button>
                                    </form>
                                @endif

                                <!-- Notation finale -->
                                @if($stage->defense_date && !$stage->final_grade)
                                    <form action="{{ route('stages.grade-defense', $stage) }}" method="POST" class="space-y-3 bg-slate-50 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-800 h-fit">
                                        @csrf
                                        <h3 class="font-bold text-sm text-gray-900 dark:text-white">Attribuer la note finale</h3>
                                        
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Note académique (sur 20)</label>
                                            <input type="number" name="final_grade" min="0" max="20" step="0.25" required placeholder="Ex: 16.5" class="w-full border rounded-lg p-2 text-xs">
                                        </div>

                                        <button type="submit" class="bg-emerald-600 text-white w-full py-2 rounded-lg text-xs font-semibold shadow hover:bg-emerald-500">
                                            Valider la note académique
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Carnet de bord (Weekly logs) -->
                <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 shadow rounded-2xl p-6 space-y-6">
                    <div class="flex justify-between items-center pb-2 border-b">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Carnet de Bord Hebdomadaire</h2>
                        <span class="text-xs text-slate-400">{{ $stage->weeklyReports->count() }} semaines rapportées</span>
                    </div>

                    <!-- Formulaire soumission étudiant -->
                    @if(Auth::id() === $stage->student_id && $stage->status === 'Convention validée')
                        <div x-data="{ open: false }">
                            <button @click="open = !open" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-4 py-2 rounded-xl text-xs font-bold border border-indigo-100 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Remplir la semaine de suivi
                            </button>

                            <form x-show="open" action="{{ route('stages.weekly-report.store', $stage) }}" method="POST" class="mt-4 p-6 bg-slate-50 dark:bg-gray-900/30 rounded-2xl border border-gray-100 dark:border-gray-800 space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Numéro de semaine</label>
                                        <input type="number" name="week_number" required min="1" placeholder="Ex: 1" class="w-full border rounded-lg p-2 text-xs">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tâches réalisées</label>
                                    <textarea name="tasks_done" required rows="3" placeholder="Description des tâches accomplies..." class="w-full border rounded-lg p-2 text-xs"></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Difficultés rencontrées (optionnel)</label>
                                    <textarea name="difficulties" rows="2" placeholder="Obstacles ou points de blocage..." class="w-full border rounded-lg p-2 text-xs"></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Observations / Remarques (optionnel)</label>
                                    <textarea name="observations" rows="2" placeholder="Autre information utile..." class="w-full border rounded-lg p-2 text-xs"></textarea>
                                </div>

                                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-bold shadow hover:bg-indigo-500">
                                    Soumettre la semaine
                                </button>
                            </form>
                        </div>
                    @endif

                    <!-- Liste des rapports hebdomadaires -->
                    <div class="space-y-4">
                        @forelse($stage->weeklyReports->sortByDesc('week_number') as $report)
                            <div class="p-5 bg-slate-50 dark:bg-gray-900/40 border border-gray-100 dark:border-gray-800 rounded-2xl space-y-3">
                                <div class="flex justify-between items-center">
                                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Semaine {{ $report->week_number }}</h3>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-semibold px-2 py-0.5 rounded 
                                            @if($report->status === 'Validé') bg-emerald-100 text-emerald-800
                                            @else bg-amber-100 text-amber-800
                                            @endif">
                                            {{ $report->status }}
                                        </span>

                                        @if(Auth::id() === $stage->supervisor_id && $report->status === 'En attente')
                                            <form action="{{ route('stages.weekly-report.validate', [$stage, $report]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1 rounded text-xxs font-bold shadow">
                                                    Valider
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                <div class="text-xs text-gray-600 dark:text-gray-400 space-y-2">
                                    <div>
                                        <p class="font-semibold text-gray-800 dark:text-gray-300">Tâches réalisées :</p>
                                        <p class="whitespace-pre-line bg-white dark:bg-gray-900/30 p-2 rounded mt-1 border border-gray-100 dark:border-gray-800">{{ $report->tasks_done }}</p>
                                    </div>
                                    @if($report->difficulties)
                                        <div>
                                            <p class="font-semibold text-gray-800 dark:text-gray-300">Difficultés rencontrées :</p>
                                            <p class="whitespace-pre-line bg-rose-50/20 p-2 rounded mt-1 border border-rose-100/50">{{ $report->difficulties }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic text-center py-6">Aucune semaine de suivi enregistrée.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
