<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">
            Mon profil etudiant
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

        <form method="POST" action="{{ route('student.profile.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">Téléphone</label>
                    <input type="text" name="phone" value="{{ old('phone', $student?->phone) }}" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-medium">Ville</label>
                    <input type="text" name="city" value="{{ old('city', $student?->city) }}" class="w-full border rounded p-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">École</label>
                    <input type="text" name="school" value="{{ old('school', $student?->school) }}" class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block font-medium">Niveau d'étude</label>
                    <input type="text" name="level" value="{{ old('level', $student?->level) }}" placeholder="Licence 3, Master 1..." class="w-full border rounded p-2">
                </div>
            </div>

            <div>
                <label class="block font-medium">Date de naissance</label>
                <input type="date" name="birth_date" value="{{ old('birth_date', $student?->birth_date) }}" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block font-medium">Bio</label>
                <textarea name="bio" rows="4" class="w-full border rounded p-2">{{ old('bio', $student?->bio) }}</textarea>
            </div>

            <div>
                <label class="block font-medium">Compétences</label>
                <textarea name="skills" rows="3" class="w-full border rounded p-2" placeholder="PHP, Laravel, JavaScript...">{{ old('skills', $student?->skills) }}</textarea>
            </div>

            <div>
                <label class="block font-medium">Projets</label>
                <textarea name="projects" rows="4" class="w-full border rounded p-2">{{ old('projects', $student?->projects) }}</textarea>
            </div>

            <div>
                <label class="block font-medium">Expériences</label>
                <textarea name="experiences" rows="4" class="w-full border rounded p-2">{{ old('experiences', $student?->experiences) }}</textarea>
            </div>

            <div>
                <label class="block font-medium">Certifications</label>
                <textarea name="certifications" rows="3" class="w-full border rounded p-2">{{ old('certifications', $student?->certifications) }}</textarea>
            </div>

            <div>
                <label class="block font-medium">Photo de profil</label>
                <input type="file" name="photo" class="w-full border rounded p-2">
                @if($student?->photo)
                    <p class="text-sm text-gray-600 mt-1">Photo actuelle conservée si aucun nouveau fichier n'est choisi.</p>
                @endif
            </div>

            <div>
                <label class="block font-medium">CV PDF</label>
                <input type="file" name="cv" class="w-full border rounded p-2">
                @if($student?->cv)
                    <a href="{{ asset('storage/'.$student->cv) }}" target="_blank" class="inline-block text-blue-600 mt-1">
                        Voir le CV actuel
                    </a>
                @endif
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">
                Enregistrer
            </button>
        </form>
    </div>
</x-app-layout>
