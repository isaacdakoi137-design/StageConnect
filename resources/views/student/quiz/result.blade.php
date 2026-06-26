<x-app-layout>
    <div class="max-w-3xl mx-auto py-10 px-4">
        <!-- Result card header -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-xl overflow-hidden mb-8">
            <div class="p-8 text-center text-white bg-gradient-to-br 
                @if($attempt->score >= 80) from-emerald-600 to-teal-700
                @elseif($attempt->score >= 50) from-amber-500 to-orange-600
                @else from-rose-600 to-red-700
                @endif">
                
                <h1 class="text-3xl font-black">Résultat de votre Évaluation</h1>
                <p class="text-white/80 text-sm mt-1">{{ $quiz->title }}</p>

                <!-- Score indicator -->
                <div class="mt-6 flex flex-col items-center">
                    <span class="text-6xl font-black tracking-tight">{{ $attempt->score }}%</span>
                    <span class="text-xs font-semibold uppercase tracking-widest mt-2 px-3 py-1 bg-white/20 rounded-full">
                        @if($attempt->score >= 80) Excellent
                        @elseif($attempt->score >= 50) Satisfaisant
                        @else À retravailler
                        @endif
                    </span>
                </div>
            </div>

            <!-- Badge Award announcement -->
            @if($attempt->score >= 80 && strtolower($quiz->domain) === 'php/laravel')
                <div class="bg-indigo-50 dark:bg-indigo-950/40 p-6 text-center border-b border-indigo-100 dark:border-indigo-900">
                    <span class="text-3xl mb-2 block">🏆</span>
                    <h3 class="font-extrabold text-indigo-900 dark:text-indigo-300 text-lg">Badge obtenu !</h3>
                    <p class="text-sm text-indigo-700 dark:text-indigo-400 mt-1">Félicitations, vous avez débloqué le badge <strong>Développeur Laravel confirmé</strong>. Il est désormais affiché sur votre profil.</p>
                </div>
            @endif

            <!-- Details -->
            <div class="p-8 space-y-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Détail des réponses</h2>

                <div class="space-y-4">
                    @foreach($quiz->questions as $index => $question)
                        @php
                            $log = $attempt->answers[$question->id] ?? null;
                            $status = $log['status'] ?? 'incorrect';
                        @endphp
                        <div class="p-4 rounded-2xl border flex items-start gap-3 
                            @if($status === 'correct') bg-emerald-50/20 border-emerald-100 dark:bg-emerald-950/10 dark:border-emerald-900/30
                            @else bg-rose-50/20 border-rose-100 dark:bg-rose-950/10 dark:border-rose-900/30
                            @endif">
                            
                            <span class="w-6 h-6 rounded-full flex items-center justify-center font-bold text-xs mt-0.5
                                @if($status === 'correct') bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300
                                @else bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300
                                @endif">
                                {{ $index + 1 }}
                            </span>

                            <div class="flex-grow">
                                <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ $question->question_text }}</h3>

                                @if($question->is_code_exercise)
                                    @if($status === 'correct')
                                        <div class="mt-2 text-xs font-semibold text-emerald-700 dark:text-emerald-400 space-y-1">
                                            <p>✓ Tous les cas de test ont été validés avec succès.</p>
                                            @if(!empty($log['details']))
                                                <ul class="list-disc list-inside mt-1 font-mono text-xxs bg-emerald-50/50 dark:bg-emerald-950/30 p-2 rounded-lg">
                                                    @foreach($log['details'] as $detail)
                                                        <li>{{ $detail }}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-xs text-rose-700 dark:text-rose-400 mt-2 font-mono">
                                            ✗ {{ $log['error'] ?? 'Échec de validation des cas de test.' }}
                                        </p>
                                    @endif
                                @else
                                    <p class="text-xs text-gray-500 mt-1">
                                        @if($status === 'correct')
                                            ✓ Votre réponse est correcte.
                                        @else
                                            ✗ Réponse incorrecte. (Bonne réponse : {{ $question->options[$question->correct_option] ?? 'N/A' }})
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('quizzes.index') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2.5 rounded-xl font-semibold shadow transition-all">
                Retour au centre d'évaluation
            </a>
        </div>
    </div>
</x-app-layout>
