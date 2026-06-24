<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-3xl font-bold mb-6">
            Publier une offre
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

        <form method="POST" action="{{ route('offers.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium">Titre</label>
                <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded p-2" required>
            </div>

            <div>
                <label class="block font-medium">Entreprise</label>
                <input type="text" name="company" value="{{ old('company') }}" class="w-full border rounded p-2" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">Lieu</label>
                    <input type="text" name="location" value="{{ old('location') }}" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="block font-medium">Domaine</label>
                    <input type="text" name="domain" value="{{ old('domain') }}" class="w-full border rounded p-2" placeholder="Informatique, reseaux...">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium">Niveau d'etude</label>
                    <input type="text" name="education_level" value="{{ old('education_level') }}" class="w-full border rounded p-2" placeholder="Licence 3, Master 1...">
                </div>

                <div>
                    <label class="block font-medium">Salaire</label>
                    <input type="number" step="0.01" min="0" name="salary" value="{{ old('salary') }}" class="w-full border rounded p-2">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block font-medium">Duree</label>
                    <input type="text" name="duration" value="{{ old('duration') }}" class="w-full border rounded p-2" placeholder="3 mois, 6 mois...">
                </div>

                <div>
                    <label class="block font-medium">Type</label>
                    <select name="contract_type" class="w-full border rounded p-2" required>
                        <option value="Stage" @selected(old('contract_type', 'Stage') === 'Stage')>Stage</option>
                        <option value="Emploi" @selected(old('contract_type') === 'Emploi')>Emploi</option>
                        <option value="Alternance" @selected(old('contract_type') === 'Alternance')>Alternance</option>
                    </select>
                </div>

                <div>
                    <label class="block font-medium">Mode</label>
                    <select name="work_mode" class="w-full border rounded p-2" required>
                        <option value="Presentiel" @selected(old('work_mode', 'Presentiel') === 'Presentiel')>Presentiel</option>
                        <option value="Teletravail" @selected(old('work_mode') === 'Teletravail')>Teletravail</option>
                        <option value="Hybride" @selected(old('work_mode') === 'Hybride')>Hybride</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block font-medium">Competences requises</label>
                <textarea name="required_skills" rows="3" class="w-full border rounded p-2" placeholder="PHP, Laravel, JavaScript...">{{ old('required_skills') }}</textarea>
            </div>

            <div>
                <label class="block font-medium">Description</label>
                <textarea name="description" rows="6" class="w-full border rounded p-2" required>{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block font-medium">Date limite</label>
                <input type="date" name="deadline" value="{{ old('deadline') }}" class="w-full border rounded p-2">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">
                Publier l'offre
            </button>
        </form>
    </div>
</x-app-layout>
