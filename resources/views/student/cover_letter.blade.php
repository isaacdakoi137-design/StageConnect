<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <!-- Breadcrumb / Header -->
        <div class="mb-8">
            <a href="{{ route('student.cv') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour au CV Intelligent
            </a>
            <h1 class="text-3xl font-extrabold tracking-tight mt-2 text-gray-900 dark:text-white">Générateur de Lettre de Motivation</h1>
            <p class="text-gray-500 mt-2">Générez une lettre de motivation personnalisée en un clic en fonction de votre profil et de l'offre ciblée.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="{ 
            offerId: '', 
            customPitch: '',
            loading: false,
            letterText: '',
            generate() {
                if(!this.offerId) {
                    alert('Veuillez sélectionner une offre.');
                    return;
                }
                this.loading = true;
                fetch('{{ route('student.cover-letter.generate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        offer_id: this.offerId,
                        custom_pitch: this.customPitch
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.letterText = data.letter;
                    this.loading = false;
                })
                .catch(err => {
                    console.error(err);
                    this.loading = false;
                });
            },
            copy() {
                navigator.clipboard.writeText(this.letterText);
                alert('Lettre de motivation copiée dans le presse-papiers !');
            }
        }">
            <!-- Formulaire -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 h-fit lg:col-span-1 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Cible : Sélectionner l'offre d'emploi</label>
                    <select x-model="offerId" class="w-full border rounded-xl p-2.5 bg-gray-50 dark:bg-gray-900 dark:text-white border-gray-200 dark:border-gray-700 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choisir une offre --</option>
                        @foreach($offers as $offer)
                            <option value="{{ $offer->id }}">{{ $offer->title }} - {{ $offer->company }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Votre touche personnelle / Pitch (optionnel)</label>
                    <textarea x-model="customPitch" rows="4" placeholder="Ex: J'adore particulièrement votre culture d'entreprise axée sur l'open source..." class="w-full border rounded-xl p-2.5 bg-gray-50 dark:bg-gray-900 dark:text-white border-gray-200 dark:border-gray-700 focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
                </div>

                <button @click="generate()" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-3 rounded-xl font-semibold shadow transition-all inline-flex justify-center items-center gap-2">
                    <span x-show="loading" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                    Générer la lettre
                </button>
            </div>

            <!-- Résultat -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8 h-full min-h-[400px] flex flex-col">
                    <div class="flex justify-between items-center mb-4 border-b pb-4 border-gray-100 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Aperçu du courrier</h2>
                        
                        <button x-show="letterText" @click="copy()" class="text-sm text-indigo-600 hover:text-indigo-500 font-semibold inline-flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m-6 4h6m-6 4h6m-6-8h4"/></svg>
                            Copier le texte
                        </button>
                    </div>

                    <!-- Texte généré -->
                    <div class="flex-grow">
                        <template x-if="!letterText && !loading">
                            <div class="flex flex-col items-center justify-center h-full text-center text-gray-400 py-12">
                                <svg class="w-16 h-16 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="font-medium">Aucune lettre générée</p>
                                <p class="text-xs mt-1">Sélectionnez une offre à gauche et cliquez sur "Générer la lettre".</p>
                            </div>
                        </template>

                        <template x-if="loading">
                            <div class="flex flex-col items-center justify-center h-full text-center py-12">
                                <span class="w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin mb-3"></span>
                                <p class="text-sm text-gray-500 font-semibold">Génération en cours...</p>
                            </div>
                        </template>

                        <div x-show="letterText && !loading" class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl border border-gray-100 dark:border-gray-800 text-gray-800 dark:text-gray-200 font-mono text-sm whitespace-pre-line leading-relaxed shadow-inner overflow-auto max-h-[500px]" x-text="letterText">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
