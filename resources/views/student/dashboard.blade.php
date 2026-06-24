<x-app-layout>
    <div class="max-w-7xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">
            Dashboard Etudiant
        </h1>

        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white shadow rounded p-6">
                <h2 class="text-xl font-semibold">
                    Nombre de candidatures
                </h2>

                <p class="text-3xl text-blue-600 mt-2">
                    {{ $applicationsCount }}
                </p>
            </div>

            <div class="bg-white shadow rounded p-6">
                <h2 class="text-xl font-semibold">
                    Actions rapides
                </h2>

                <div class="mt-3 flex flex-wrap gap-3">
                    <a href="{{ route('offers.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Voir les offres
                    </a>
                    <a href="{{ route('student.profile') }}" class="border px-4 py-2 rounded">
                        Completer mon profil
                    </a>
                </div>
            </div>
        </div>

        <!-- Profil Étudiant -->
        @if($student)
            <div class="bg-white shadow rounded p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">
                    Mon Profil
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">
                            Compétences
                        </h3>
                        @if($student->skills)
                            <p class="text-gray-700">{{ $student->skills }}</p>
                        @else
                            <p class="text-gray-400 italic">Non renseigné</p>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">
                            Projets
                        </h3>
                        @if($student->projects)
                            <p class="text-gray-700">{{ $student->projects }}</p>
                        @else
                            <p class="text-gray-400 italic">Non renseigné</p>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">
                            Expériences
                        </h3>
                        @if($student->experiences)
                            <p class="text-gray-700">{{ $student->experiences }}</p>
                        @else
                            <p class="text-gray-400 italic">Non renseigné</p>
                        @endif
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-600 mb-2">
                            Certifications
                        </h3>
                        @if($student->certifications)
                            <p class="text-gray-700">{{ $student->certifications }}</p>
                        @else
                            <p class="text-gray-400 italic">Non renseigné</p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Candidatures -->
        <div class="bg-white shadow rounded p-6">
            <h2 class="text-xl font-semibold mb-4">
                Mes candidatures
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full border">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 text-left">Offre</th>
                            <th class="p-2 text-left">Entreprise</th>
                            <th class="p-2 text-left">Statut</th>
                            <th class="p-2 text-left">Compatibilité</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($applicationsWithMatching as $application)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-2">{{ $application->offer->title }}</td>
                                <td class="p-2">{{ $application->offer->company }}</td>
                                <td class="p-2">
                                    <span class="px-2 py-1 rounded text-sm 
                                        @if($application->status === 'Acceptée') bg-green-200 text-green-800
                                        @elseif($application->status === 'Refusée') bg-red-200 text-red-800
                                        @else bg-yellow-200 text-yellow-800
                                        @endif">
                                        {{ $application->status }}
                                    </span>
                                </td>
                                <td class="p-2">
                                    @if($application->match_percentage > 0)
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $application->match_percentage }}%"></div>
                                            </div>
                                            <span class="text-sm font-semibold">{{ $application->match_percentage }}%</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center">
                                    Aucune candidature
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
