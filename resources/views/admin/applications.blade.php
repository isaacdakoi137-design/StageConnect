<x-app-layout>
    <div class="max-w-7xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">
            Gestion des candidatures
        </h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border bg-white">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left">Etudiant</th>
                    <th class="p-2 text-left">Offre</th>
                    <th class="p-2 text-left">Statut</th>
                    <th class="p-2 text-left">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($applications as $application)
                    <tr class="border-t">
                        <td class="p-2">
                            {{ $application->user->name }}
                        </td>

                        <td class="p-2">
                            {{ $application->offer->title }}
                        </td>

                        <td class="p-2">
                            {{ $application->status }}
                        </td>

                        <td class="p-2">
                            <form action="{{ route('admin.applications.update', $application) }}" method="POST" class="flex gap-2">
                                @csrf
                                @method('PATCH')

                                <select name="status" class="border rounded p-1">
                                    <option value="En attente" @selected($application->status === 'En attente')>En attente</option>
                                    <option value="Acceptee" @selected($application->status === 'Acceptee')>Acceptee</option>
                                    <option value="Refusee" @selected($application->status === 'Refusee')>Refusee</option>
                                </select>

                                <button class="bg-blue-600 text-white px-3 py-1 rounded">
                                    Modifier
                                </button>
                            </form>
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
</x-app-layout>
