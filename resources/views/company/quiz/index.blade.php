<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-4">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Gestion des Tests Techniques</h1>
                <p class="text-gray-500 mt-2">Créez des QCM et exercices de codage pour évaluer les compétences des candidats.</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('quizzes.create') }}" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl font-medium inline-flex items-center gap-2 shadow transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Créer un quiz
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 p-4 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Liste des quiz -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Mes Quiz</h2>
                    
                    <div class="space-y-4">
                        @forelse($quizzes as $quiz)
                            <div class="p-4 bg-slate-50 dark:bg-gray-900/40 rounded-xl border border-gray-100 dark:border-gray-800 flex justify-between items-center">
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white">{{ $quiz->title }}</h3>
                                    <p class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold mt-0.5">Domaine : {{ $quiz->domain }}</p>
                                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($quiz->description, 100) }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-semibold bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 px-2.5 py-1 rounded-full border border-indigo-100 dark:border-indigo-900">
                                        {{ $quiz->questions->count() }} questions
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic text-center py-6">Aucun quiz créé pour le moment.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tentatives des candidats -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Résultats candidats</h2>

                    <div class="space-y-4">
                        @forelse($attempts as $attempt)
                            <div class="p-4 bg-slate-50 dark:bg-gray-900/40 rounded-xl border border-gray-100 dark:border-gray-800 space-y-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $attempt->user->name }}</h4>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $attempt->quiz->title }}</p>
                                    </div>
                                    <span class="text-xs font-bold px-2 py-1 rounded-full 
                                        @if($attempt->score >= 80) bg-green-100 text-green-800
                                        @elseif($attempt->score >= 50) bg-amber-100 text-amber-800
                                        @else bg-red-100 text-red-800
                                        @endif">
                                        {{ $attempt->score }}%
                                    </span>
                                </div>
                                <p class="text-xxs text-gray-400 text-right">{{ \Carbon\Carbon::parse($attempt->completed_at)->format('d/m/Y à H:i') }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic text-center py-6">Aucune tentative pour le moment.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
