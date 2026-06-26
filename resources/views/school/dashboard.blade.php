<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Tableau de Bord Académique</h1>
            <p class="text-gray-500 mt-2">Suivi des conventions, accompagnement des stages et organisation des soutenances.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 p-4 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-sm font-semibold uppercase text-gray-400">Taux de Placement Étudiants</h3>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-4xl font-black text-indigo-600 dark:text-indigo-400">{{ $placementRate }}%</span>
                    <span class="text-xs text-gray-500">({{ $placedStudents }} sur {{ $totalStudents }} étudiants)</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 h-2 rounded-full mt-3">
                    <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $placementRate }}%"></div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-sm font-semibold uppercase text-gray-400">Conventions en Attente</h3>
                <span class="text-4xl font-black text-amber-600 mt-2 block">{{ $pendingConventions->count() }}</span>
                <p class="text-xs text-gray-500 mt-1">Conventions requérant une validation académique.</p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                <h3 class="text-sm font-semibold uppercase text-gray-400">Soutenances à Planifier</h3>
                <span class="text-4xl font-black text-blue-600 mt-2 block">{{ $readyForDefense->count() }}</span>
                <p class="text-xs text-gray-500 mt-1">Rapports finaux déposés en attente de jury.</p>
            </div>
        </div>

        <!-- Section tables tabbed -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow p-8 space-y-6" x-data="{ tab: 'conventions' }">
            
            <!-- Navigation des onglets -->
            <div class="flex gap-4 border-b border-gray-100 dark:border-gray-700 pb-3">
                <button @click="tab = 'conventions'" :class="tab === 'conventions' ? 'border-indigo-600 text-indigo-600 font-bold border-b-2' : 'text-gray-400 font-medium hover:text-gray-600 dark:hover:text-gray-200'" class="pb-2 text-sm transition-all">
                    Conventions en attente ({{ $pendingConventions->count() }})
                </button>
                <button @click="tab = 'active'" :class="tab === 'active' ? 'border-indigo-600 text-indigo-600 font-bold border-b-2' : 'text-gray-400 font-medium hover:text-gray-600 dark:hover:text-gray-200'" class="pb-2 text-sm transition-all">
                    Stages en cours ({{ $activeStages->count() }})
                </button>
                <button @click="tab = 'defenses'" :class="tab === 'defenses' ? 'border-indigo-600 text-indigo-600 font-bold border-b-2' : 'text-gray-400 font-medium hover:text-gray-600 dark:hover:text-gray-200'" class="pb-2 text-sm transition-all">
                    Soutenances & Notes ({{ $readyForDefense->count() }})
                </button>
                <button @click="tab = 'archive'" :class="tab === 'archive' ? 'border-indigo-600 text-indigo-600 font-bold border-b-2' : 'text-gray-400 font-medium hover:text-gray-600 dark:hover:text-gray-200'" class="pb-2 text-sm transition-all">
                    Historique ({{ $completedStages->count() }})
                </button>
            </div>

            <!-- Contenu des onglets -->
            
            <!-- Conventions -->
            <div x-show="tab === 'conventions'" class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs text-gray-400 border-b pb-2">
                                <th class="pb-2 font-bold uppercase">Étudiant</th>
                                <th class="pb-2 font-bold uppercase">Entreprise</th>
                                <th class="pb-2 font-bold uppercase">Poste</th>
                                <th class="pb-2 font-bold uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingConventions as $pc)
                                <tr class="border-b last:border-0 hover:bg-slate-50/50 dark:hover:bg-gray-900/20 text-sm">
                                    <td class="py-3 font-semibold text-gray-900 dark:text-white">{{ $pc->student->name }}</td>
                                    <td class="py-3 text-gray-600 dark:text-gray-300">{{ $pc->offer->company }}</td>
                                    <td class="py-3 text-gray-500">{{ $pc->offer->title }}</td>
                                    <td class="py-3 text-right">
                                        <div class="inline-flex gap-2">
                                            <form action="{{ route('stages.validate-convention', $pc) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white px-3 py-1.5 rounded-xl text-xs font-semibold shadow">
                                                    Valider la convention
                                                </button>
                                            </form>
                                            <a href="{{ route('stages.show', $pc) }}" class="border text-slate-600 hover:bg-slate-50 px-3 py-1.5 rounded-xl text-xs font-semibold">
                                                Détails
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-400 italic">Aucune convention en attente de validation académique.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Active stages -->
            <div x-show="tab === 'active'" class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs text-gray-400 border-b pb-2">
                                <th class="pb-2 font-bold uppercase">Étudiant</th>
                                <th class="pb-2 font-bold uppercase">Entreprise</th>
                                <th class="pb-2 font-bold uppercase">Encadreur</th>
                                <th class="pb-2 font-bold uppercase">Période</th>
                                <th class="pb-2 font-bold uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeStages as $as)
                                <tr class="border-b last:border-0 hover:bg-slate-50/50 dark:hover:bg-gray-900/20 text-sm">
                                    <td class="py-3 font-semibold text-gray-900 dark:text-white">{{ $as->student->name }}</td>
                                    <td class="py-3 text-gray-600 dark:text-gray-300">{{ $as->offer->company }}</td>
                                    <td class="py-3 text-gray-500">{{ $as->supervisor->name ?? 'Non assigné' }}</td>
                                    <td class="py-3 text-slate-500 text-xs">
                                        {{ $as->start_date ? \Carbon\Carbon::parse($as->start_date)->format('d/m') : '' }} - {{ $as->end_date ? \Carbon\Carbon::parse($as->end_date)->format('d/m/Y') : '' }}
                                    </td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('stages.show', $as) }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1.5 rounded-xl text-xs font-semibold shadow">
                                            Suivi du stage
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400 italic">Aucun stage actif en cours d'encadrement.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Soutenances -->
            <div x-show="tab === 'defenses'" class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs text-gray-400 border-b pb-2">
                                <th class="pb-2 font-bold uppercase">Étudiant</th>
                                <th class="pb-2 font-bold uppercase">Date Soutenance</th>
                                <th class="pb-2 font-bold uppercase">Jury</th>
                                <th class="pb-2 font-bold uppercase">Note finale</th>
                                <th class="pb-2 font-bold uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($readyForDefense as $rd)
                                <tr class="border-b last:border-0 hover:bg-slate-50/50 dark:hover:bg-gray-900/20 text-sm">
                                    <td class="py-3 font-semibold text-gray-900 dark:text-white">{{ $rd->student->name }}</td>
                                    <td class="py-3 text-gray-600 dark:text-gray-300">
                                        {{ $rd->defense_date ? \Carbon\Carbon::parse($rd->defense_date)->format('d/m/Y à H:i') : 'Non planifiée' }}
                                    </td>
                                    <td class="py-3 text-gray-500 text-xs">{{ $rd->jury_members ?? 'Non assigné' }}</td>
                                    <td class="py-3">
                                        <span class="text-xs font-bold bg-amber-50 text-amber-700 px-2 py-0.5 rounded">En attente note</span>
                                    </td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('stages.show', $rd) }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1.5 rounded-xl text-xs font-semibold shadow">
                                            Gérer l'évaluation
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400 italic">Aucune soutenance en attente de planification ou de notation.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Historique -->
            <div x-show="tab === 'archive'" class="space-y-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-xs text-gray-400 border-b pb-2">
                                <th class="pb-2 font-bold uppercase">Étudiant</th>
                                <th class="pb-2 font-bold uppercase">Entreprise</th>
                                <th class="pb-2 font-bold uppercase">Poste</th>
                                <th class="pb-2 font-bold uppercase">Note finale</th>
                                <th class="pb-2 font-bold uppercase text-right">Fiche</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($completedStages as $cs)
                                <tr class="border-b last:border-0 hover:bg-slate-50/50 dark:hover:bg-gray-900/20 text-sm">
                                    <td class="py-3 font-semibold text-gray-900 dark:text-white">{{ $cs->student->name }}</td>
                                    <td class="py-3 text-gray-600 dark:text-gray-300">{{ $cs->offer->company }}</td>
                                    <td class="py-3 text-gray-500">{{ $cs->offer->title }}</td>
                                    <td class="py-3 font-bold text-teal-600">{{ $cs->final_grade }}/20</td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('stages.show', $cs) }}" class="border text-slate-600 hover:bg-slate-50 px-3 py-1.5 rounded-xl text-xs font-semibold">
                                            Consulter
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400 italic">Aucun stage archivé dans l'historique.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
