<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 px-4">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between bg-gradient-to-r from-slate-900 to-indigo-950 text-white p-8 rounded-2xl shadow-xl">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">Mon CV Intelligent</h1>
                <p class="text-indigo-200 mt-2 text-sm md:text-base">Complétez vos informations et laissez le système générer instantanément votre CV PDF et vos lettres de motivation adaptées aux offres.</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-3">
                <a href="{{ route('student.cv.print') }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl font-medium inline-flex items-center gap-2 shadow-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Imprimer / Sauvegarder PDF
                </a>
                <a href="{{ route('student.cover-letter') }}" class="bg-white hover:bg-slate-100 text-indigo-950 px-5 py-2.5 rounded-xl font-medium inline-flex items-center gap-2 shadow-md transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Lettre de motivation
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Aperçu rapide du profil -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-6 flex flex-col items-center text-center h-fit">
                <div class="w-28 h-28 bg-indigo-50 dark:bg-indigo-900 rounded-full flex items-center justify-center border-4 border-indigo-100 dark:border-indigo-800 shadow-inner mb-4 overflow-hidden">
                    @if($student && $student->photo)
                        <img src="{{ asset('storage/' . $student->photo) }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl text-indigo-600 dark:text-indigo-300 font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                    @endif
                </div>
                
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h2>
                <p class="text-sm text-indigo-600 dark:text-indigo-400 font-semibold mt-1">{{ $student?->level ?? 'Étudiant' }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $student?->school ?? 'Aucun établissement renseigné' }}</p>

                <div class="w-full border-t border-gray-100 dark:border-gray-700 my-6"></div>

                <!-- Badges -->
                <div class="w-full">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white text-left mb-3">Mes Badges obtenus</h3>
                    <div class="flex flex-wrap gap-2 justify-start">
                        @forelse(Auth::user()->badges as $badge)
                            <div class="flex items-center gap-1.5 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-700 dark:text-indigo-300 px-3 py-1.5 rounded-full text-xs font-semibold border border-indigo-100 dark:border-indigo-900">
                                <span>{{ $badge->name }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic text-left">Aucun badge pour le moment.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Éditeur interactif -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-100 dark:border-gray-700 p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Détails du CV</h2>
                        <a href="{{ route('student.profile') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium inline-flex items-center gap-1">
                            Modifier mon profil
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    @if(!$student)
                        <div class="bg-amber-50 border border-amber-200 text-amber-800 p-4 rounded-xl text-center">
                            Vous n'avez pas encore configuré votre profil étudiant. Veuillez cliquer sur le bouton ci-dessus pour le créer.
                        </div>
                    @else
                        <div class="space-y-6">
                            <!-- Bloc Formation -->
                            <div class="p-4 bg-slate-50 dark:bg-gray-900/50 rounded-xl">
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Formation & École</h3>
                                <p class="text-gray-900 dark:text-white font-bold">{{ $student->school }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">{{ $student->level }}</p>
                            </div>

                            <!-- Bloc Compétences -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">Compétences clés</h3>
                                <div class="flex flex-wrap gap-2">
                                    @php
                                        $skills = $student->skills ? explode(';', $student->skills) : [];
                                    @endphp
                                    @forelse($skills as $skill)
                                        <span class="bg-slate-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-lg text-xs font-semibold">
                                            {{ trim($skill) }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-400 italic">Aucune compétence renseignée.</span>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Bloc Projets -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Projets réalisés</h3>
                                <div class="prose prose-sm text-gray-700 dark:text-gray-300 max-w-none whitespace-pre-line bg-slate-50 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                                    {{ $student->projects ?: 'Aucun projet listé.' }}
                                </div>
                            </div>

                            <!-- Bloc Expériences -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Expériences professionnelles</h3>
                                <div class="prose prose-sm text-gray-700 dark:text-gray-300 max-w-none whitespace-pre-line bg-slate-50 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                                    {{ $student->experiences ?: 'Aucune expérience listée.' }}
                                </div>
                            </div>

                            <!-- Bloc Certifications -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Certifications</h3>
                                <div class="prose prose-sm text-gray-700 dark:text-gray-300 max-w-none whitespace-pre-line bg-slate-50 dark:bg-gray-900/30 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                                    {{ $student->certifications ?: 'Aucune certification listée.' }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
