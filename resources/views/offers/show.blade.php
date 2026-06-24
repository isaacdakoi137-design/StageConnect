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
                <div class="mt-6 bg-blue-50 border border-blue-100 rounded p-4">
                    <h2 class="text-xl font-semibold mb-2">Compatibilité avec votre profil</h2>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-blue-700 font-semibold">{{ $matchPercentage }}%</span>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $matchPercentage }}%"></div>
                        </div>
                    </div>

                    @if(!empty($matchingSkills))
                        <p class="text-sm text-gray-700">
                            Compétences correspondantes : {{ implode(', ', $matchingSkills) }}
                        </p>
                    @else
                        <p class="text-sm text-gray-700">
                            Aucun skill correspondant trouvé pour le moment.
                        </p>
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
