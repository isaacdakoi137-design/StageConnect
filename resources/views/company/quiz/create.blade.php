<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <!-- Header -->
        <div class="mb-8">
            <a href="{{ route('quizzes.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 inline-flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Retour aux tests
            </a>
            <h1 class="text-3xl font-extrabold tracking-tight mt-2 text-gray-900 dark:text-white">Créer un nouveau Test Technique</h1>
        </div>

        <form method="POST" action="{{ route('quizzes.store') }}" class="space-y-6" x-data="{
            questions: [
                { text: '', type: 'qcm', options: ['', '', '', ''], correct: 0, starter: '', test_cases: '[]' }
            ],
            addQuestion() {
                this.questions.push({ text: '', type: 'qcm', options: ['', '', '', ''], correct: 0, starter: '', test_cases: '[]' });
            },
            removeQuestion(index) {
                this.questions.splice(index, 1);
            }
        }">
            @csrf

            <!-- Informations principales -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow p-6 space-y-4">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Informations Générales</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Titre du Test</label>
                        <input type="text" name="title" required placeholder="Ex: Quiz de base PHP/Laravel" class="w-full border rounded-xl p-2.5 bg-gray-50 dark:bg-gray-900 dark:text-white border-gray-200 dark:border-gray-700 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Domaine technologique</label>
                        <input type="text" name="domain" required placeholder="Ex: PHP/Laravel, JavaScript..." class="w-full border rounded-xl p-2.5 bg-gray-50 dark:bg-gray-900 dark:text-white border-gray-200 dark:border-gray-700 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Description (optionnel)</label>
                    <textarea name="description" rows="3" placeholder="Évaluez les connaissances de base..." class="w-full border rounded-xl p-2.5 bg-gray-50 dark:bg-gray-900 dark:text-white border-gray-200 dark:border-gray-700 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
            </div>

            <!-- Questions dynamiques -->
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Questions & Exercices</h2>
                    <button type="button" @click="addQuestion()" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 px-4 py-2 rounded-xl font-semibold text-sm flex items-center gap-1 border border-indigo-100 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Ajouter une question
                    </button>
                </div>

                <template x-for="(q, index) in questions" :key="index">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow p-6 space-y-4 relative">
                        <button type="button" @click="removeQuestion(index)" x-show="questions.length > 1" class="absolute top-4 right-4 text-gray-400 hover:text-rose-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>

                        <div class="flex items-center gap-4">
                            <span class="w-8 h-8 rounded-full bg-slate-100 dark:bg-gray-700 flex items-center justify-center font-bold text-slate-700 dark:text-gray-300 text-sm" x-text="index + 1"></span>
                            
                            <div class="flex-grow">
                                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Type de question</label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" :name="'questions['+index+'][type]'" value="qcm" x-model="q.type" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">QCM (Choix multiple)</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" :name="'questions['+index+'][type]'" value="code" x-model="q.type" class="text-indigo-600 focus:ring-indigo-500">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Exercice de Code (PHP)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Énoncé de la question</label>
                            <textarea :name="'questions['+index+'][text]'" required rows="2" placeholder="Ex: Quelle est la fonction PHP pour trouver le plus grand nombre ?" class="w-full border rounded-xl p-2.5 bg-gray-50 dark:bg-gray-900 dark:text-white border-gray-200 dark:border-gray-700 focus:ring-indigo-500 focus:border-indigo-500 text-sm"></textarea>
                        </div>

                        <!-- Options QCM -->
                        <div x-show="q.type === 'qcm'" class="space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Options de réponse (Cochez la bonne réponse)</label>
                            <template x-for="(opt, optIndex) in q.options" :key="optIndex">
                                <div class="flex items-center gap-2">
                                    <input type="radio" :name="'questions['+index+'][correct]'" :value="optIndex" x-model="q.correct" class="text-emerald-600 focus:ring-emerald-500">
                                    <input type="text" :name="'questions['+index+'][options]['+optIndex+']'" required placeholder="Option de réponse" class="w-full border rounded-xl p-2 bg-gray-50 dark:bg-gray-900 dark:text-white border-gray-200 dark:border-gray-700 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                </div>
                            </template>
                        </div>

                        <!-- Exercice de Code -->
                        <div x-show="q.type === 'code'" class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Code de départ (Starter template)</label>
                                <textarea :name="'questions['+index+'][starter]'" rows="4" placeholder="function findMax($arr) {&#10;    // Code ici&#10;}" class="w-full border rounded-xl p-2.5 bg-gray-900 text-emerald-400 font-mono text-sm border-gray-700 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Cas de Test (JSON)</label>
                                <textarea :name="'questions['+index+'][test_cases]'" rows="3" placeholder='[&#10;    {"input": "[1, 5, 3]", "output": "5"},&#10;    {"input": "[-10, 0, -2]", "output": "0"}&#10;]' class="w-full border rounded-xl p-2.5 bg-gray-900 text-indigo-400 font-mono text-sm border-gray-700 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-xl font-semibold shadow transition-all">
                Enregistrer le Test Technique
            </button>
        </form>
    </div>
</x-app-layout>
