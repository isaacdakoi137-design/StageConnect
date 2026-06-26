<x-app-layout>
    <div class="max-w-4xl mx-auto py-10 px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Notifications</h1>
            <p class="text-gray-500 mt-2">Restez informé des offres, entretiens et validations de stage en temps réel.</p>
        </div>

        <div class="bg-white dark:bg-gray-800 border rounded-3xl shadow overflow-hidden divide-y dark:divide-gray-700">
            @forelse($notifications as $notif)
                <div class="p-6 flex items-start gap-4 hover:bg-slate-50/50 dark:hover:bg-gray-900/20 transition-all 
                    @if(!$notif->read_at) bg-indigo-50/20 dark:bg-indigo-950/10 @endif">
                    
                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-950 flex items-center justify-center font-bold text-indigo-600 text-xs shrink-0 mt-0.5">
                        🔔
                    </div>

                    <div class="flex-grow space-y-1">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-sm text-gray-950 dark:text-white">{{ $notif->title }}</h3>
                            <span class="text-xxs text-gray-400 font-semibold">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed">{{ $notif->message }}</p>

                        @if($notif->link)
                            <a href="{{ $notif->link }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-500 mt-2 inline-block">
                                En savoir plus →
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 italic text-center py-10">Aucune notification.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
