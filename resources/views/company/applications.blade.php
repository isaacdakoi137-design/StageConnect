<x-app-layout>
    <div class="max-w-7xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">
            Candidatures recues
        </h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                Certains champs doivent etre corriges.
            </div>
        @endif

        <table class="w-full border bg-white">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 text-left">Etudiant</th>
                    <th class="p-2 text-left">Offre</th>
                    <th class="p-2 text-left">Message</th>
                    <th class="p-2 text-left">Statut</th>
                    <th class="p-2 text-left">Profil</th>
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
                            {{ $application->message ?? 'Aucun message' }}
                        </td>

                        <td class="p-2">
                            {{ $application->status }}
                        </td>

                        <td class="p-2">
                            <a href="{{ route('company.candidate.show', $application->user) }}" class="bg-blue-600 text-white px-3 py-1 rounded">
                                Voir le profil
                            </a>
                        </td>

                        <td class="p-2">
                            <form action="{{ route('company.applications.update', $application) }}" method="POST" class="space-y-2">
                                @csrf
                                @method('PATCH')

                                <div class="space-y-2">
                                    <select name="status" onchange="this.form?.submit()" class="border rounded p-1 w-full text-sm"
                                        @if($application->status !== 'En attente') disabled @endif>
                                        <option value="En attente" @selected($application->status === 'En attente')>En attente</option>
                                        <option value="Acceptee" @selected($application->status === 'Acceptee')>Acceptée</option>
                                        <option value="Refusee" @selected($application->status === 'Refusee')>Refusée</option>
                                    </select>

                                    @if($application->status === 'En attente')
                                        <button type="button" onclick="toggleDateForm(this)" class="text-xs text-blue-600 hover:underline">
                                            Définir dates
                                        </button>
                                        <div class="date-form hidden space-y-2 mt-2">
                                            <div>
                                                <label class="block text-xs text-gray-600">Date début</label>
                                                <input type="date" name="start_date" class="border rounded p-1 w-full text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs text-gray-600">Date fin</label>
                                                <input type="date" name="end_date" class="border rounded p-1 w-full text-sm">
                                            </div>
                                        </div>
                                    @elseif($application->status === 'Acceptee')
                                        <p class="text-xs text-gray-500 bg-green-50 p-1 rounded">✓ Candidature acceptée</p>
                                    @else
                                        <p class="text-xs text-gray-500 bg-red-50 p-1 rounded">✗ Candidature refusée</p>
                                    @endif
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center">
                            Aucune candidature recue
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function toggleDateForm(button) {
            const form = button.nextElementSibling;
            form.classList.toggle('hidden');
        }
    </script>
</x-app-layout>
