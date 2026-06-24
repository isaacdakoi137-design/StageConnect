<x-app-layout>
    <div class="max-w-7xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">
            Mes Candidatures
        </h1>

        <div class="space-y-4">
            @forelse($applications as $application)
                <div class="bg-white border rounded-lg p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-gray-900">
                                {{ $application->offer->title }}
                            </h2>
                            <p class="text-gray-600 mt-1">
                                {{ $application->offer->company }} - {{ $application->offer->location }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($application->status === 'Acceptée') bg-green-100 text-green-800
                            @elseif($application->status === 'Refusée') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            {{ $application->status }}
                        </span>
                    </div>

                    @if($application->message)
                        <div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded">
                            <p class="text-sm text-gray-600"><strong>Votre message :</strong></p>
                            <p class="text-gray-700 mt-1">{{ $application->message }}</p>
                        </div>
                    @endif

                    <div class="mt-4 flex items-center justify-between text-sm text-gray-500">
                        <p>Candidature envoyée le {{ $application->created_at->format('d/m/Y à H:i') }}</p>
                        <a href="{{ route('offers.show', $application->offer) }}" class="text-blue-600 hover:underline">
                            Voir l'offre
                        </a>
                    </div>
                </div>
            @empty
                <div class="border rounded p-6 text-center bg-white">
                    <p class="text-gray-500 mb-2">Aucune candidature pour le moment</p>
                    <a href="{{ route('offers.index') }}" class="text-blue-600 hover:underline">
                        Parcourir les offres
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>