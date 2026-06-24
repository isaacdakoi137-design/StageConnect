<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">
            Profil du candidat
        </h1>

        @if($user->student)
            @if($user->student->photo)
                <img src="{{ asset('storage/'.$user->student->photo) }}" class="w-40 h-40 rounded-full object-cover mb-6">
            @endif

            <div class="bg-white border rounded p-6 space-y-3">
                <p><strong>Nom :</strong> {{ $user->name }}</p>
                <p><strong>Email :</strong> {{ $user->email }}</p>
                <p><strong>Telephone :</strong> {{ $user->student->phone ?? 'Non renseigne' }}</p>
                <p><strong>Ville :</strong> {{ $user->student->city ?? 'Non renseignee' }}</p>
                <p><strong>Ecole :</strong> {{ $user->student->school ?? 'Non renseignee' }}</p>
                <p><strong>Niveau :</strong> {{ $user->student->level ?? 'Non renseigne' }}</p>
                <p><strong>Bio :</strong> {{ $user->student->bio ?? 'Non renseignee' }}</p>
                <p><strong>Competences :</strong> {{ $user->student->skills ?? 'Non renseignees' }}</p>
                <p><strong>Projets :</strong> {{ $user->student->projects ?? 'Non renseignes' }}</p>
                <p><strong>Experiences :</strong> {{ $user->student->experiences ?? 'Non renseignees' }}</p>
                <p><strong>Certifications :</strong> {{ $user->student->certifications ?? 'Non renseignees' }}</p>

                @if($user->student->cv)
                    <a href="{{ asset('storage/'.$user->student->cv) }}" target="_blank" class="inline-block bg-green-600 text-white px-4 py-2 rounded">
                        Telecharger le CV
                    </a>
                @endif
            </div>
        @else
            <div class="bg-white border rounded p-6">
                Aucun profil renseigne.
            </div>
        @endif
    </div>
</x-app-layout>
