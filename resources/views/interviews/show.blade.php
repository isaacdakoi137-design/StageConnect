<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4 h-[calc(100vh-120px)] flex flex-col" x-data="{
        audioMuted: false,
        videoMuted: false,
        screenShared: false,
        elapsedTime: 0,
        init() {
            setInterval(() => {
                this.elapsedTime++;
            }, 1000);
        },
        formatTime() {
            let m = Math.floor(this.elapsedTime / 60);
            let s = this.elapsedTime % 60;
            return (m < 10 ? '0' : '') + m + ':' + (s < 10 ? '0' : '') + s;
        }
    }">
        <!-- Top bar inside Room -->
        <div class="flex justify-between items-center bg-slate-900 text-white px-6 py-4 rounded-t-3xl border-b border-slate-800">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-rose-500 animate-ping"></span>
                <h1 class="text-lg font-bold tracking-tight">Salon d'Entretien StageConnect</h1>
                <span class="text-xs font-semibold bg-slate-800 px-2 py-1 rounded text-slate-400">ID: {{ substr($interview->video_room_id, 0, 8) }}</span>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-sm font-mono text-slate-300 font-bold" x-text="formatTime()"></div>
                <a href="{{ route('interviews.index') }}" class="bg-rose-600 hover:bg-rose-500 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow transition-all">
                    Quitter le salon
                </a>
            </div>
        </div>

        <!-- Main Room Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-4 bg-slate-950 flex-grow rounded-b-3xl overflow-hidden min-h-[500px]">
            <!-- Video Feeds area -->
            <div class="lg:col-span-3 p-6 flex flex-col justify-between relative h-full">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-grow">
                    
                    <!-- Candidate feed -->
                    <div class="bg-slate-900 rounded-2xl relative border border-slate-800 overflow-hidden flex flex-col justify-center items-center shadow-lg group">
                        <!-- Simulated video stream -->
                        <div class="absolute inset-0 bg-slate-900 flex flex-col justify-center items-center">
                            @role('Entreprise')
                                <!-- Student user initial/photo -->
                                <div class="w-24 h-24 rounded-full bg-indigo-600 border-4 border-indigo-400 flex items-center justify-center text-3xl font-bold text-white shadow-inner animate-pulse">
                                    {{ substr($application->user->name, 0, 1) }}
                                </div>
                                <span class="text-xs text-indigo-400 mt-4 font-semibold tracking-wider">Candidat : {{ $application->user->name }} (Caméra Active)</span>
                            @else
                                <!-- Company user initial -->
                                <div class="w-24 h-24 rounded-full bg-slate-700 border-4 border-slate-500 flex items-center justify-center text-3xl font-bold text-white shadow-inner animate-pulse">
                                    {{ substr($application->offer->company, 0, 1) }}
                                </div>
                                <span class="text-xs text-slate-400 mt-4 font-semibold tracking-wider">Recruteur (Caméra Active)</span>
                            @endrole
                        </div>
                        
                        <div class="absolute bottom-4 left-4 bg-black/60 px-3 py-1.5 rounded-lg text-xs font-semibold text-white">
                            @role('Entreprise') {{ $application->user->name }} @else Recruteur @endrole
                        </div>
                    </div>

                    <!-- My Local Feed -->
                    <div class="bg-slate-900 rounded-2xl relative border border-slate-800 overflow-hidden flex flex-col justify-center items-center shadow-lg">
                        <div class="absolute inset-0 bg-slate-900 flex flex-col justify-center items-center transition-all duration-300" :class="videoMuted ? 'opacity-80' : ''">
                            
                            <template x-if="!videoMuted">
                                <div class="flex flex-col items-center">
                                    <div class="w-24 h-24 rounded-full bg-emerald-600 border-4 border-emerald-400 flex items-center justify-center text-3xl font-bold text-white shadow-inner animate-pulse">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="text-xs text-emerald-400 mt-4 font-semibold tracking-wider">Vous (Ma Caméra)</span>
                                </div>
                            </template>
                            
                            <template x-if="videoMuted">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-slate-800 border-2 border-slate-700 flex items-center justify-center text-slate-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                    </div>
                                    <span class="text-xs text-slate-500 mt-4 font-semibold">Caméra désactivée</span>
                                </div>
                            </template>
                        </div>

                        <div class="absolute bottom-4 left-4 bg-black/60 px-3 py-1.5 rounded-lg text-xs font-semibold text-white">
                            Vous
                        </div>
                    </div>
                </div>

                <!-- Controls Bar -->
                <div class="flex justify-center items-center gap-4 bg-slate-900/80 backdrop-blur border border-slate-800 p-4 rounded-2xl mt-6">
                    <button @click="audioMuted = !audioMuted" class="p-3.5 rounded-xl border border-slate-800 text-white font-semibold transition-all hover:bg-slate-800" :class="audioMuted ? 'bg-rose-600 border-rose-600 hover:bg-rose-500' : 'bg-slate-950'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!audioMuted"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="audioMuted"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/></svg>
                    </button>

                    <button @click="videoMuted = !videoMuted" class="p-3.5 rounded-xl border border-slate-800 text-white font-semibold transition-all hover:bg-slate-800" :class="videoMuted ? 'bg-rose-600 border-rose-600 hover:bg-rose-500' : 'bg-slate-950'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!videoMuted"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="videoMuted"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                    </button>

                    <button @click="screenShared = !screenShared" class="p-3.5 rounded-xl border border-slate-800 text-white font-semibold transition-all hover:bg-slate-800" :class="screenShared ? 'bg-emerald-600 border-emerald-600 hover:bg-emerald-500' : 'bg-slate-950'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </button>
                </div>
            </div>

            <!-- Recruiter Review Sidebar / Student Info sidebar -->
            <div class="lg:col-span-1 border-l border-slate-800 bg-slate-900 p-6 flex flex-col justify-between">
                @role('Entreprise')
                    <div class="space-y-4">
                        <h2 class="text-base font-bold text-white uppercase tracking-wider">Notes & Évaluation</h2>
                        <p class="text-xs text-slate-400">Rédigez vos notes en temps réel sur le candidat.</p>

                        <form action="{{ route('interviews.report', $interview) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Résultat</label>
                                <select name="status" class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl p-2.5 text-sm focus:ring-indigo-500">
                                    <option value="Programmé">Programmé</option>
                                    <option value="Complété">Valider le candidat (Accepté)</option>
                                    <option value="Annulé">Refuser le candidat</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-400 mb-1">Notes d'entretien</label>
                                <textarea name="report_summary" required rows="10" placeholder="Excellente communication. Compétences en Laravel solides..." class="w-full bg-slate-950 border border-slate-800 text-white rounded-xl p-2.5 text-xs focus:ring-indigo-500">{{ $interview->report_summary }}</textarea>
                            </div>

                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-500 text-white py-2.5 rounded-xl text-xs font-semibold shadow">
                                Enregistrer l'évaluation
                            </button>
                        </form>
                    </div>
                @else
                    <div class="space-y-4 text-white">
                        <h2 class="text-base font-bold uppercase tracking-wider text-indigo-400">Infos sur l'offre</h2>
                        <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800 space-y-2">
                            <h3 class="font-bold text-sm">{{ $application->offer->title }}</h3>
                            <p class="text-xs text-slate-400">{{ $application->offer->company }}</p>
                            <p class="text-xs text-slate-500">{{ $application->offer->location }}</p>
                        </div>

                        <h2 class="text-base font-bold uppercase tracking-wider text-indigo-400 mt-6">Conseils d'entretien</h2>
                        <ul class="list-disc list-inside text-xs text-slate-300 space-y-1.5 bg-slate-950/60 p-4 rounded-xl border border-slate-800">
                            <li>Parlez clairement et doucement.</li>
                            <li>Détaillez vos projets personnels Laravel.</li>
                            <li>Posez des questions sur l'équipe technique de l'entreprise.</li>
                        </ul>
                    </div>
                @endrole

                <div class="text-center text-xxs text-slate-500 border-t border-slate-800 pt-4">
                    Salon vidéo StageConnect sécurisé
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
