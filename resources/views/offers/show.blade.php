<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white border rounded p-6">
            <h1 class="text-3xl font-bold">
                {{ $offer->title }}
            </h1>

            <p class="mt-2 text-gray-700">
                {{ $offer->company }} - {{ $offer->location }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6 text-sm">
                <p><strong>Domaine :</strong> {{ $offer->domain ?? 'Non precise' }}</p>
                <p><strong>Niveau :</strong> {{ $offer->education_level ?? 'Non precise' }}</p>
                <p><strong>Salaire :</strong> {{ $offer->salary ? number_format($offer->salary, 0, ',', ' ') : 'Non precise' }}</p>
                <p><strong>Duree :</strong> {{ $offer->duration ?? 'Non precise' }}</p>
                <p><strong>Type :</strong> {{ $offer->contract_type }}</p>
                <p><strong>Mode :</strong> {{ $offer->work_mode }}</p>
                <p><strong>Date limite :</strong> {{ $offer->deadline ?? 'Non precisee' }}</p>
            </div>

            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-2">Compétences requises</h2>
                <p class="whitespace-pre-line">{{ $offer->required_skills ?? 'Non précisées' }}</p>
            </div>

            @if(isset($student) && $student && $matchPercentage !== null)
                <div class="mt-6 bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-gray-900 dark:to-slate-900 border border-indigo-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 .364l-.707 .707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548 .547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        Analyse de compatibilité IA
                    </h2>
                    
                    <div class="flex items-center gap-4 mb-4">
                        <div class="relative flex items-center justify-center">
                            <span class="text-2xl font-black text-indigo-700 dark:text-indigo-400">{{ $matchPercentage }}%</span>
                        </div>
                        <div class="flex-grow">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="bg-indigo-600 h-3 rounded-full transition-all duration-500" style="width: {{ $matchPercentage }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mt-4">
                        <!-- Acquis -->
                        <div class="p-3 bg-emerald-50/50 dark:bg-emerald-950/20 rounded-xl border border-emerald-100/50 dark:border-emerald-900/30">
                            <h4 class="font-bold text-emerald-800 dark:text-emerald-400 mb-1.5 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Compétences acquises
                            </h4>
                            @if(!empty($matchingSkills))
                                <div class="flex flex-wrap gap-1">
                                    @foreach($matchingSkills as $skill)
                                        <span class="bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 px-2 py-0.5 rounded text-xs font-semibold">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-gray-500 italic">Aucune correspondance.</p>
                            @endif
                        </div>

                        <!-- Manquant -->
                        <div class="p-3 bg-rose-50/50 dark:bg-rose-950/20 rounded-xl border border-rose-100/50 dark:border-rose-900/30">
                            <h4 class="font-bold text-rose-800 dark:text-rose-400 mb-1.5 flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                Compétences manquantes
                            </h4>
                            @if(!empty($missingSkills))
                                <div class="flex flex-wrap gap-1">
                                    @foreach($missingSkills as $skill)
                                        <span class="bg-rose-100 dark:bg-rose-900/50 text-rose-800 dark:text-rose-300 px-2 py-0.5 rounded text-xs font-semibold">{{ $skill }}</span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Vous possédez toutes les compétences requises !</p>
                            @endif
                        </div>
                    </div>

                    <!-- Conseils -->
                    @if(!empty($advice))
                        <div class="mt-4 p-4 bg-indigo-50/40 dark:bg-indigo-950/10 rounded-xl border border-indigo-100/40 dark:border-indigo-900/20 text-xs">
                            <h4 class="font-bold text-indigo-900 dark:text-indigo-300 mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Conseils pour améliorer votre profil
                            </h4>
                            <ul class="list-disc list-inside space-y-1 text-indigo-950 dark:text-indigo-200">
                                @foreach($advice as $tip)
                                    <li>{!! $tip !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @endif

            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-2">Description</h2>
                <p class="whitespace-pre-line">{{ $offer->description }}</p>
            </div>
        </div>

        @role('Etudiant')
            <form method="POST" action="{{ route('applications.store', $offer) }}" class="bg-white border rounded p-6 mt-6">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium">
                        Message de motivation
                    </label>
                    <textarea name="message" rows="4" class="w-full border rounded p-2">{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">
                    Postuler
                </button>
            </form>
        @endrole
    </div>
</x-app-layout>
