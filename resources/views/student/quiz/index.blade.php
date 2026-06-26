<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Centre d'Évaluation Technique</h1>
            <p class="text-gray-500 mt-2">Mesurez et validez vos compétences pour obtenir des badges et maximiser votre compatibilité avec les offres de stage.</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-200 text-red-700 p-4 rounded-xl mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($quizzes as $quiz)
                @php
                    $attempt = $myAttempts->where('quiz_id', $quiz->id)->first();
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/60 px-3 py-1.5 rounded-lg border border-indigo-100 dark:border-indigo-900">
                                {{ $quiz->domain }}
                            </span>
                            
                            @if($attempt)
                                <span class="text-xs font-bold px-2.5 py-1.5 rounded-lg 
                                    @if($attempt->score >= 80) bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300
                                    @elseif($attempt->score >= 50) bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300
                                    @else bg-rose-100 text-rose-800 dark:bg-rose-950/50 dark:text-rose-300
                                    @endif">
                                    Complété : {{ $attempt->score }}%
                                </span>
                            @else
                                <span class="text-xs font-bold bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-gray-300 px-2.5 py-1.5 rounded-lg">
                                    Disponible
                                </span>
                            @endif
                        </div>

                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $quiz->title }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $quiz->description ?: 'Aucune description disponible.' }}</p>
                    </div>

                    <div class="border-t border-gray-100 dark:border-gray-700 pt-4 flex items-center justify-between">
                        <span class="text-xs text-gray-400">{{ $quiz->questions->count() }} questions</span>
                        
                        @if($attempt)
                            <button disabled class="bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 cursor-not-allowed px-4 py-2 rounded-xl text-sm font-semibold">
                                Déjà passé
                            </button>
                        @else
                            <a href="{{ route('quizzes.take', $quiz) }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2 rounded-xl text-sm font-semibold shadow transition-all">
                                Commencer le test
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 italic text-center col-span-2 py-10 bg-white dark:bg-gray-800 border rounded-2xl">
                    Aucun test technique disponible pour le moment.
                </p>
            @endforelse
        </div>
    </div>
</x-app-layout>
