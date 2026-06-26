<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Réseau Professionnel StageConnect</h1>
            <p class="text-gray-500 mt-2">Partagez vos projets, certifications, échangez avec les professionnels et développez vos contacts.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 p-4 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Left Sidebar (User profile summary & connections count) -->
            <div class="space-y-6 lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow p-6 text-center">
                    <div class="w-20 h-20 bg-indigo-50 dark:bg-indigo-900 rounded-full flex items-center justify-center border-2 border-indigo-100 dark:border-indigo-800 shadow-inner mb-3 mx-auto overflow-hidden">
                        @if(Auth::user()->student && Auth::user()->student->photo)
                            <img src="{{ asset('storage/' . Auth::user()->student->photo) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-3xl text-indigo-600 dark:text-indigo-300 font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        @endif
                    </div>
                    <h2 class="font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ Auth::user()->email }}</p>

                    <div class="border-t my-4"></div>

                    <div class="text-left space-y-2">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Contacts</span>
                            <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $connections->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feed Center (Post form & feeds) -->
            <div class="space-y-6 lg:col-span-2">
                <!-- Post form -->
                <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow p-6">
                    <form action="{{ route('network.post.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div class="flex gap-3">
                            <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-900 rounded-full flex items-center justify-center font-bold text-indigo-600 shrink-0 text-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <textarea name="content" required placeholder="Quoi de neuf ? Partagez un projet, une certification ou une recherche de stage..." class="w-full border-0 focus:ring-0 resize-none text-sm bg-transparent dark:text-white" rows="3"></textarea>
                        </div>
                        
                        <div class="border-t pt-3 flex justify-between items-center">
                            <!-- Image Attachment input -->
                            <label class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-indigo-600 cursor-pointer">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Ajouter photo</span>
                                <input type="file" name="image" class="hidden">
                            </label>
                            
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 py-1.5 rounded-xl text-xs font-semibold shadow">
                                Publier
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Feed list -->
                <div class="space-y-4">
                    @forelse($posts as $post)
                        <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow p-6 space-y-4">
                            <!-- User identity -->
                            <div class="flex items-start justify-between">
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-900 rounded-full flex items-center justify-center font-bold text-indigo-600 shrink-0 text-sm">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="font-bold text-gray-900 dark:text-white text-sm">{{ $post->user->name }}</h3>
                                            @foreach($post->user->badges as $badge)
                                                <span class="text-xs" title="{{ $badge->name }}">{{ $badge->name }}</span>
                                            @endforeach
                                        </div>
                                        <p class="text-xxs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($post->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Post Content -->
                            <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed whitespace-pre-line">
                                {{ $post->content }}
                            </p>

                            @if($post->image_path)
                                <div class="rounded-xl overflow-hidden border">
                                    <img src="{{ asset('storage/' . $post->image_path) }}" class="w-full h-auto object-cover max-h-[350px]">
                                </div>
                            @endif

                            <!-- Interaction Bar -->
                            <div class="border-t pt-3 flex items-center gap-4 text-xs text-gray-500">
                                <form action="{{ route('network.post.like', $post) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-1 hover:text-indigo-600 font-semibold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        <span>J'aime ({{ $post->likes_count }})</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400 italic text-center py-10 bg-white border rounded-2xl shadow">
                            Aucune publication sur le réseau pour le moment. Soyez le premier !
                        </p>
                    @endforelse
                </div>
            </div>

            <!-- Right Sidebar (Incoming Requests & Suggestions) -->
            <div class="space-y-6 lg:col-span-1">
                <!-- Invitations reçues -->
                @if($incomingRequests->count() > 0)
                    <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow p-6 space-y-4">
                        <h2 class="font-bold text-gray-900 dark:text-white text-sm pb-2 border-b">Invitations reçues</h2>
                        <div class="space-y-3">
                            @foreach($incomingRequests as $req)
                                <div class="flex items-center justify-between text-xs gap-2">
                                    <div>
                                        <p class="font-bold text-gray-900 dark:text-white">{{ $req->user->name }}</p>
                                    </div>
                                    <form action="{{ route('network.connect.accept', $req) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="bg-indigo-600 text-white px-2 py-1 rounded font-bold shadow hover:bg-indigo-500">
                                            Accepter
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Suggestions de contacts -->
                <div class="bg-white dark:bg-gray-800 border rounded-2xl shadow p-6 space-y-4">
                    <h2 class="font-bold text-gray-900 dark:text-white text-sm pb-2 border-b">Suggestions de contacts</h2>
                    <div class="space-y-4">
                        @forelse($suggestions as $sug)
                            <div class="flex items-center justify-between gap-2">
                                <div class="text-xs">
                                    <p class="font-bold text-gray-900 dark:text-white">{{ $sug->name }}</p>
                                    <p class="text-gray-400 mt-0.5">
                                        @if($sug->hasRole('Etudiant')) Étudiant @elseif($sug->hasRole('Entreprise')) Entreprise @else Encadreur @endif
                                    </p>
                                </div>
                                <form action="{{ route('network.connect', $sug) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-indigo-50 text-indigo-600 hover:bg-indigo-100 px-2 py-1 rounded text-xs font-bold border border-indigo-100">
                                        + Ajouter
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">Aucune suggestion.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
