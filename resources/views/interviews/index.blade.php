<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Mes Entretiens de Recrutement</h1>
            <p class="text-gray-500 mt-2">Suivez vos entretiens programmés, accédez aux salles vidéo virtuelles, et consultez les comptes-rendus.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 p-4 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-900/60 text-left border-b border-gray-100 dark:border-gray-700">
                            <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                @role('Entreprise') Candidat @else Entreprise @endrole
                            </th>
                            <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Poste</th>
                            <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Date & Heure</th>
                            <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Statut</th>
                            <th class="p-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($interviews as $interview)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-gray-900/20">
                                <td class="p-4">
                                    <span class="font-semibold text-gray-900 dark:text-white">
                                        @role('Entreprise')
                                            {{ $interview->application->user->name }}
                                        @else
                                            {{ $interview->application->offer->company }}
                                        @endrole
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-300">
                                        {{ $interview->application->offer->title }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">
                                        {{ \Carbon\Carbon::parse($interview->scheduled_at)->format('d/m/Y à H:i') }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full 
                                        @if($interview->status === 'Programmé') bg-indigo-50 text-indigo-700 dark:bg-indigo-950 dark:text-indigo-300
                                        @elseif($interview->status === 'Complété') bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300
                                        @else bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300
                                        @endif">
                                        {{ $interview->status }}
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="inline-flex gap-2">
                                        @if($interview->status === 'Programmé')
                                            <a href="{{ route('interviews.show', $interview) }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow transition-all flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                Rejoindre
                                            </a>
                                        @endif

                                        @role('Entreprise')
                                            <!-- Button to add report -->
                                            <button @click="$dispatch('open-report-modal', { id: {{ $interview->id }}, text: '{{ addslashes($interview->report_summary) }}', status: '{{ $interview->status }}' })" class="border text-slate-600 hover:bg-slate-50 px-4 py-2 rounded-xl text-xs font-semibold transition-all">
                                                Gérer
                                            </button>
                                        @else
                                            @if($interview->report_summary)
                                                <button @click="alert('{{ addslashes($interview->report_summary) }}')" class="border text-slate-600 hover:bg-slate-50 px-4 py-2 rounded-xl text-xs font-semibold transition-all">
                                                    Voir compte-rendu
                                                </button>
                                            @endif
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-gray-400 italic">
                                    Aucun entretien programmé.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Alpine-based Modal for Company to Submit Report -->
        @role('Entreprise')
            <div x-data="{ open: false, id: null, text: '', status: 'Programmé' }" 
                 @open-report-modal.window="open = true; id = $event.detail.id; text = $event.detail.text; status = $event.detail.status"
                 x-show="open" 
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 z-50"
                 x-cloak>
                <div class="bg-white dark:bg-gray-800 rounded-3xl border shadow-xl p-8 max-w-lg w-full space-y-4" @click.away="open = false">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Évaluer le candidat & Compte-rendu</h3>
                    
                    <form :action="'{{ url('/company/interviews') }}/' + id + '/report'" method="POST" class="space-y-4">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Statut final</label>
                            <select name="status" x-model="status" class="w-full border rounded-xl p-2.5 bg-gray-50 dark:bg-gray-900 dark:text-white border-gray-200 dark:border-gray-700">
                                <option value="Programmé">Programmé</option>
                                <option value="Complété">Complété (Évaluation positive)</option>
                                <option value="Annulé">Annulé</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Résumé / Compte-rendu d'entretien</label>
                            <textarea name="report_summary" x-model="text" required rows="5" placeholder="Le candidat a fait bonne impression, possède un bon niveau sur Laravel..." class="w-full border rounded-xl p-2.5 bg-gray-50 dark:bg-gray-900 dark:text-white border-gray-200 dark:border-gray-700 text-sm"></textarea>
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" @click="open = false" class="border text-slate-600 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-slate-50">Annuler</button>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2 rounded-xl text-sm font-semibold shadow">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        @endrole
    </div>
</x-app-layout>
