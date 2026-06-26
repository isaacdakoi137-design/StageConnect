<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV - {{ $user->name }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (via CDN to ensure compilation without Vite in printing contexts) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    <style>
        @media print {
            body {
                background-color: white !important;
                color: black !important;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased">
    
    <!-- Actions Bar (No print) -->
    <div class="no-print bg-white shadow border-b py-4 px-6 flex justify-between items-center max-w-5xl mx-auto mt-4 rounded-xl">
        <div class="flex items-center gap-2">
            <span class="w-3.5 h-3.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-sm font-semibold text-slate-700">Aperçu avant impression de votre CV</span>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-medium text-sm flex items-center gap-1.5 shadow transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Imprimer / PDF
            </button>
            <button onclick="window.close()" class="border text-slate-600 px-4 py-2 rounded-lg font-medium text-sm hover:bg-slate-50 transition-all">
                Fermer
            </button>
        </div>
    </div>

    <!-- CV Container -->
    <div class="max-w-5xl mx-auto my-8 bg-white shadow-xl border rounded-2xl p-12 print:shadow-none print:border-none print:my-0 print:p-0">
        
        <!-- Header / Identity -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b pb-8 mb-8 border-slate-200">
            <div>
                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900">{{ $user->name }}</h1>
                <p class="text-xl text-indigo-600 font-semibold mt-1">{{ $student->level }}</p>
                <p class="text-slate-500 text-sm mt-1">{{ $student->school }}</p>
            </div>
            <div class="mt-4 md:mt-0 text-left md:text-right text-sm text-slate-600 space-y-1">
                <p class="flex items-center md:justify-end gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L22 8m-2 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V10/"></svg>
                    {{ $user->email }}
                </p>
                @if($student->phone)
                    <p class="flex items-center md:justify-end gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ $student->phone }}
                    </p>
                @endif
                @if($student->city)
                    <p class="flex items-center md:justify-end gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $student->city }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            
            <!-- Left Column: About, Skills, Badges -->
            <div class="md:col-span-1 space-y-8 border-r pr-6 border-slate-100 print:border-none print:pr-0">
                
                <!-- Bio -->
                @if($student->bio)
                    <div>
                        <h2 class="text-xs uppercase font-extrabold tracking-wider text-slate-500 mb-3">À propos</h2>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $student->bio }}</p>
                    </div>
                @endif

                <!-- Skills -->
                <div>
                    <h2 class="text-xs uppercase font-extrabold tracking-wider text-slate-500 mb-3">Compétences</h2>
                    <div class="flex flex-wrap gap-1.5">
                        @php
                            $skills = $student->skills ? explode(';', $student->skills) : [];
                        @endphp
                        @foreach($skills as $skill)
                            <span class="bg-slate-100 text-slate-800 px-2.5 py-1 rounded text-xs font-medium border border-slate-200">
                                {{ trim($skill) }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <!-- Certifications -->
                @if($student->certifications)
                    <div>
                        <h2 class="text-xs uppercase font-extrabold tracking-wider text-slate-500 mb-3">Certifications</h2>
                        <div class="text-sm text-slate-600 whitespace-pre-line leading-relaxed">
                            {{ $student->certifications }}
                        </div>
                    </div>
                @endif

                <!-- Badges (Mini references) -->
                @if($user->badges->count() > 0)
                    <div>
                        <h2 class="text-xs uppercase font-extrabold tracking-wider text-slate-500 mb-3">Badges StageConnect</h2>
                        <div class="space-y-1.5">
                            @foreach($user->badges as $badge)
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-slate-700">
                                    <span>{{ $badge->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Projects, Experiences -->
            <div class="md:col-span-2 space-y-8">
                
                <!-- Experiences -->
                <div>
                    <h2 class="text-xs uppercase font-extrabold tracking-wider text-slate-500 mb-4 pb-1 border-b border-slate-100">Expériences professionnelles</h2>
                    <div class="text-sm text-slate-700 whitespace-pre-line leading-relaxed space-y-4">
                        {{ $student->experiences ?: 'Aucune expérience déclarée pour le moment.' }}
                    </div>
                </div>

                <!-- Projects -->
                <div>
                    <h2 class="text-xs uppercase font-extrabold tracking-wider text-slate-500 mb-4 pb-1 border-b border-slate-100">Projets réalisés</h2>
                    <div class="text-sm text-slate-700 whitespace-pre-line leading-relaxed space-y-4">
                        {{ $student->projects ?: 'Aucun projet déclaré pour le moment.' }}
                    </div>
                </div>

            </div>

        </div>

        <!-- Footer -->
        <div class="mt-16 pt-8 border-t text-center text-xs text-slate-400 border-slate-200">
            Document généré automatiquement via StageConnect le {{ now()->format('d/m/Y') }}
        </div>
    </div>

</body>
</html>
