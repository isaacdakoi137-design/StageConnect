<x-app-layout>
    <div class="max-w-6xl mx-auto py-10">
        <div class="flex items-center justify-between gap-4 mb-6">
            <h1 class="text-3xl font-bold">
                Offres
            </h1>

            @role('Entreprise|Admin')
                <a href="{{ route('offers.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Publier
                </a>
            @endrole
        </div>

        <form method="GET" action="{{ route('offers.index') }}" class="bg-white border rounded p-4 mb-6 grid grid-cols-1 md:grid-cols-5 gap-3">
            <input type="text" name="location" value="{{ request('location') }}" placeholder="Ville" class="border rounded p-2">
            <input type="text" name="domain" value="{{ request('domain') }}" placeholder="Domaine" class="border rounded p-2">
            <input type="number" min="0" name="min_salary" value="{{ request('min_salary') }}" placeholder="Salaire min" class="border rounded p-2">

            <select name="contract_type" class="border rounded p-2">
                <option value="">Type</option>
                <option value="Stage" @selected(request('contract_type') === 'Stage')>Stage</option>
                <option value="Emploi" @selected(request('contract_type') === 'Emploi')>Emploi</option>
                <option value="Alternance" @selected(request('contract_type') === 'Alternance')>Alternance</option>
            </select>

            <select name="work_mode" class="border rounded p-2">
                <option value="">Mode</option>
                <option value="Presentiel" @selected(request('work_mode') === 'Presentiel')>Presentiel</option>
                <option value="Teletravail" @selected(request('work_mode') === 'Teletravail')>Teletravail</option>
                <option value="Hybride" @selected(request('work_mode') === 'Hybride')>Hybride</option>
            </select>

            <div class="md:col-span-5 flex gap-3">
                <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded">
                    Filtrer
                </button>
                <a href="{{ route('offers.index') }}" class="border px-4 py-2 rounded">
                    Reinitialiser
                </a>
            </div>
        </form>

        @forelse($offers as $offer)
            <div class="border rounded p-4 mb-4 bg-white">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold">
                            {{ $offer->title }}
                        </h2>
                        <p class="text-gray-700">
                            {{ $offer->company }} - {{ $offer->location }}
                        </p>
                    </div>

                    <span class="text-sm bg-gray-100 px-3 py-1 rounded">
                        {{ $offer->contract_type }}
                    </span>
                </div>

                <div class="mt-3 text-sm text-gray-700 grid grid-cols-1 md:grid-cols-4 gap-2">
                        <p>Domaine: {{ $offer->domain ?? 'Non precise' }}</p>
                    <p>Mode: {{ $offer->work_mode ?? 'Non precise' }}</p>
                    <p>Duree: {{ $offer->duration ?? 'Non precise' }}</p>
                    <p>Salaire: {{ $offer->salary ? number_format($offer->salary, 0, ',', ' ') : 'Non precise' }}</p>
                </div>

                @if(isset($student) && $student && $offer->match_percentage !== null)
                    <div class="mt-4">
                        <div class="flex items-center gap-2 text-sm mb-2">
                            <span class="font-medium">Compatibilité :</span>
                            <span class="text-blue-600 font-semibold">{{ $offer->match_percentage }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $offer->match_percentage }}%"></div>
                        </div>
                    </div>
                @endif

                <a href="{{ route('offers.show', $offer) }}" class="inline-block mt-4 text-blue-600">
                    Voir l'offre
                </a>
            </div>
        @empty
            <div class="border rounded p-6 text-center bg-white">
                Aucune offre trouvée
            </div>
        @endforelse

        {{ $offers->links() }}
    </div>
</x-app-layout>
