<x-app-layout>
    <div class="max-w-7xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-8">
            Dashboard Administrateur
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
            <div class="bg-blue-500 text-white p-6 rounded-lg">
                <h2 class="text-xl font-bold">Etudiants</h2>
                <p class="text-4xl mt-3">{{ $students }}</p>
            </div>

            <div class="bg-indigo-500 text-white p-6 rounded-lg">
                <h2 class="text-xl font-bold">Entreprises</h2>
                <p class="text-4xl mt-3">{{ $companies }}</p>
            </div>

            <div class="bg-green-500 text-white p-6 rounded-lg">
                <h2 class="text-xl font-bold">Offres</h2>
                <p class="text-4xl mt-3">{{ $offers }}</p>
            </div>

            <div class="bg-purple-500 text-white p-6 rounded-lg">
                <h2 class="text-xl font-bold">Candidatures</h2>
                <p class="text-4xl mt-3">{{ $applications }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold">
                    Dernieres candidatures
                </h2>

                <a href="{{ route('admin.applications') }}" class="text-blue-600">
                    Tout voir
                </a>
            </div>

            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-2">Etudiant</th>
                        <th class="text-left p-2">Offre</th>
                        <th class="text-left p-2">Statut</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($latestApplications as $application)
                        <tr class="border-b">
                            <td class="p-2">{{ $application->user->name }}</td>
                            <td class="p-2">{{ $application->offer->title }}</td>
                            <td class="p-2">{{ $application->status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-4 text-center">
                                Aucune candidature
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
