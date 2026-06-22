<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">

        <h1 class="text-3xl font-bold mb-6">
            Mon Profil Étudiant
        </h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST"
            action="{{ route('student.profile.store') }}"
            enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block font-medium">Téléphone</label>
                <input type="text"
                       name="phone"
                       class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block font-medium">Ville</label>
                <input type="text"
                       name="city"
                       class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block font-medium">École</label>
                <input type="text"
                       name="school"
                       class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block font-medium">Niveau</label>
                <input type="text"
                       name="level"
                       placeholder="Licence 3, Master 1..."
                       class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block font-medium">Date de naissance</label>
                <input type="date"
                       name="birth_date"
                       class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block font-medium">Bio</label>
                <textarea name="bio"
                          rows="5"
                          class="w-full border rounded p-2"></textarea>
            </div>

            <div class="mb-4">
                <label class="block font-medium">Photo de profil</label>
                <input type="file"
                    name="photo"
                    class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label class="block font-medium">CV PDF</label>
                <input type="file"
                    name="cv"
                    class="w-full border rounded p-2">
            </div>

            <button
                type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded">
                Enregistrer
            </button>

        </form>

    </div>
</x-app-layout>