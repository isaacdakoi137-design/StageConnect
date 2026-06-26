<x-app-layout>
    <div class="max-w-3xl mx-auto py-10 px-4" x-data="{
        timeLeft: 600, // 10 minutes
        init() {
            setInterval(() => {
                if (this.timeLeft > 0) {
                    this.timeLeft--;
                } else {
                    document.getElementById('quizForm').submit();
                }
            }, 1000);
        },
        formatTime() {
            let m = Math.floor(this.timeLeft / 60);
            let s = this.timeLeft % 60;
            return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        }
    }">
        <!-- Header / Timer -->
        <div class="flex justify-between items-center bg-gradient-to-r from-indigo-900 to-indigo-950 text-white p-6 rounded-2xl shadow-lg mb-8">
            <div>
                <h1 class="text-2xl font-bold tracking-tight">{{ $quiz->title }}</h1>
                <p class="text-indigo-200 text-xs mt-1">Domaine : {{ $quiz->domain }}</p>
            </div>
            <div class="bg-indigo-900 border border-indigo-700 rounded-xl px-4 py-2 text-center shadow-inner">
                <p class="text-xxs uppercase tracking-wider text-indigo-300 font-semibold">Temps restant</p>
                <p class="text-xl font-mono font-bold" x-text="formatTime()"></p>
            </div>
        </div>

        <form id="quizForm" method="POST" action="{{ route('quizzes.submit', $quiz) }}" class="space-y-6">
            @csrf

            @foreach($quiz->questions as $index => $question)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow p-6 space-y-4">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-bold text-xs">{{ $index + 1 }}</span>
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $question->question_text }}</h2>
                    </div>

                    @if($question->is_code_exercise)
                        <!-- Code editor simulation -->
                        <div class="space-y-2">
                            <label class="block text-xs font-semibold text-gray-400 uppercase">Code PHP (Modifiez pour compléter l'exercice)</label>
                            <textarea name="answers[{{ $question->id }}]" rows="8" class="w-full bg-gray-900 text-emerald-400 font-mono text-sm border-gray-700 rounded-xl p-4 focus:ring-indigo-500 focus:border-indigo-500">{{ $question->code_starter ?: "function findMax(\$arr) {\n    // Code ici\n}" }}</textarea>
                        </div>
                    @else
                        <!-- QCM choices -->
                        <div class="grid grid-cols-1 gap-2">
                            @foreach($question->options as $optIndex => $option)
                                <label class="flex items-center p-3 bg-slate-50 dark:bg-gray-900/30 hover:bg-indigo-50/50 rounded-xl border border-gray-100 dark:border-gray-800 cursor-pointer transition-all">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $optIndex }}" class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach

            <div class="flex justify-between items-center pt-4">
                <p class="text-xs text-gray-400">Assurez-vous de vérifier toutes vos réponses avant de soumettre.</p>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-3 rounded-xl font-bold shadow transition-all">
                    Soumettre le test
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
