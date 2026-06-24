<x-app-layout>
    <div class="max-w-7xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">
            Mes Stages
        </h1>

        <div class="space-y-4">
            @forelse($stages as $stage)
                <div class="bg-white border rounded-lg p-6 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <h2 class="text-xl font-bold text-gray-900">
                                {{ $stage->offer->title }}
                            </h2>
                            <p class="text-gray-600 mt-1">
                                {{ $stage->offer->company }} - {{ $stage->offer->location }}
                            </p>
                        </div>

                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            @if($stage->status === 'En cours') bg-blue-100 text-blue-800
                            @elseif($stage->status === 'Complété') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ $stage->status }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Début</p>
                            <p class="font-medium text-gray-900">
                                {{ $stage->start_date ? \Carbon\Carbon::parse($stage->start_date)->format('d/m/Y') : 'Non fixée' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Fin</p>
                            <p class="font-medium text-gray-900">
                                {{ $stage->end_date ? \Carbon\Carbon::parse($stage->end_date)->format('d/m/Y') : 'Non fixée' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Domaine</p>
                            <p class="font-medium text-gray-900">{{ $stage->offer->domain ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Durée</p>
                            <p class="font-medium text-gray-900">{{ $stage->offer->duration ?? 'N/A' }}</p>
                        </div>
                    </div>

                    @if($stage->supervisor_id)
                        <div class="mt-4 p-3 bg-blue-50 border border-blue-100 rounded">
                            <p class="text-sm text-blue-700">
                                <strong>Encadrant :</strong> {{ $stage->supervisor->name ?? 'Non assigné' }}
                            </p>
                        </div>
                    @endif
                </div>
            @empty
                <div class="border rounded p-6 text-center bg-white">
                    <p class="text-gray-500 mb-2">Aucun stage pour le moment</p>
                    <a href="{{ route('offers.index') }}" class="text-blue-600 hover:underline">
                        Consulter les offres
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
