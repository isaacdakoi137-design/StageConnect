<x-app-layout>
    <div class="max-w-7xl mx-auto py-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold">
                Dashboard Entreprise
            </h1>

            <a href="{{ route('offers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                Publier une offre
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6 border dark:border-gray-700">
                <h2 class="text-xs uppercase tracking-wider font-bold text-gray-400">Offres publiées</h2>
                <p class="text-3xl font-black text-blue-600 dark:text-blue-400 mt-2">{{ $offersCount }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6 border dark:border-gray-700">
                <h2 class="text-xs uppercase tracking-wider font-bold text-gray-400">Candidatures reçues</h2>
                <p class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-2">{{ $applicationsCount }}</p>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-2xl p-6 border dark:border-gray-700">
                <h2 class="text-xs uppercase tracking-wider font-bold text-gray-400">Temps moyen de recrutement</h2>
                <p class="text-3xl font-black text-indigo-600 dark:text-indigo-400 mt-2">{{ $avgRecruitmentTime }} jours</p>
            </div>
        </div>

        <div class="bg-white shadow rounded p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold">
                    Mes offres
                </h2>

                <a href="{{ route('company.applications') }}" class="text-blue-600">
                    Voir les candidatures
                </a>
            </div>

            <table class="w-full border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2 text-left">Titre</th>
                        <th class="p-2 text-left">Lieu</th>
                        <th class="p-2 text-left">Type</th>
                        <th class="p-2 text-left">Mode</th>
                        <th class="p-2 text-left">Date limite</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($offers as $offer)
                        <tr class="border-t">
                            <td class="p-2">{{ $offer->title }}</td>
                            <td class="p-2">{{ $offer->location }}</td>
                            <td class="p-2">{{ $offer->contract_type }}</td>
                            <td class="p-2">{{ $offer->work_mode }}</td>
                            <td class="p-2">{{ $offer->deadline }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center">
                                Aucune offre publiee
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
