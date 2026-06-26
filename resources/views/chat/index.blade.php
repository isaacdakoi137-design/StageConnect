<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-4 h-[calc(100vh-120px)] flex flex-col">
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-6">Messagerie Instantanée</h1>

        <div class="grid grid-cols-1 lg:grid-cols-4 bg-white dark:bg-gray-800 border dark:border-gray-700 shadow-xl rounded-3xl overflow-hidden flex-grow min-h-[500px]" x-data="{
            activeUserId: '{{ $activeUser?->id ?? '' }}',
            messages: [],
            content: '',
            loading: false,
            init() {
                if (this.activeUserId) {
                    this.loadMessages();
                    // Poll messages every 3 seconds
                    setInterval(() => {
                        this.loadMessages(true);
                    }, 3000);
                }
            },
            loadMessages(silent = false) {
                if (!this.activeUserId) return;
                if (!silent) this.loading = true;
                
                fetch('{{ url('/chat/fetch') }}/' + this.activeUserId)
                    .then(res => res.json())
                    .then(data => {
                        this.messages = data;
                        this.loading = false;
                        this.scrollToBottom();
                    })
                    .catch(err => {
                        console.error(err);
                        this.loading = false;
                    });
            },
            sendMessage() {
                if (!this.content.trim() || !this.activeUserId) return;
                let text = this.content;
                this.content = '';

                fetch('{{ url('/chat/send') }}/' + this.activeUserId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ content: text })
                })
                .then(res => res.json())
                .then(data => {
                    this.loadMessages(true);
                });
            },
            scrollToBottom() {
                this.$nextTick(() => {
                    let container = document.getElementById('chatContainer');
                    if(container) {
                        container.scrollTop = container.scrollHeight;
                    }
                });
            }
        }">
            
            <!-- Users sidebar -->
            <div class="lg:col-span-1 border-r dark:border-gray-700 flex flex-col bg-slate-50 dark:bg-gray-900/40">
                <div class="p-4 border-b dark:border-gray-700">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Conversations</span>
                </div>
                <div class="flex-grow overflow-y-auto divide-y dark:divide-gray-700">
                    @forelse($chatUsers as $chatUser)
                        <a href="{{ route('chat.index', ['user_id' => $chatUser->id]) }}" class="flex items-center gap-3 p-4 hover:bg-slate-100 dark:hover:bg-gray-800 transition-all 
                            @if($activeUser?->id === $chatUser->id) bg-indigo-50/50 dark:bg-gray-800 @endif">
                            <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-950 flex items-center justify-center font-bold text-indigo-600 text-xs shrink-0 relative">
                                {{ substr($chatUser->name, 0, 1) }}
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 absolute -bottom-0.5 -right-0.5 border-2 border-white dark:border-gray-800"></span>
                            </div>
                            <div class="text-left">
                                <p class="font-bold text-sm text-gray-950 dark:text-white">{{ $chatUser->name }}</p>
                                <p class="text-xxs text-gray-400 uppercase">
                                    @if($chatUser->hasRole('Etudiant')) Étudiant @elseif($chatUser->hasRole('Entreprise')) Entreprise @else Encadreur @endif
                                </p>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-gray-400 italic p-6 text-center">Aucune discussion active. Visitez le réseau pour ajouter des contacts !</p>
                    @endforelse
                </div>
            </div>

            <!-- Messages area -->
            <div class="lg:col-span-3 flex flex-col justify-between h-full bg-white dark:bg-gray-800">
                @if($activeUser)
                    <!-- Active User Header -->
                    <div class="p-4 border-b dark:border-gray-700 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-950 flex items-center justify-center font-bold text-indigo-600 text-xs">
                            {{ substr($activeUser->name, 0, 1) }}
                        </div>
                        <div class="text-left">
                            <h2 class="font-bold text-sm text-gray-950 dark:text-white">{{ $activeUser->name }}</h2>
                            <p class="text-xxs text-gray-400 uppercase">Active</p>
                        </div>
                    </div>

                    <!-- Feed Container -->
                    <div id="chatContainer" class="flex-grow p-6 overflow-y-auto space-y-4 max-h-[350px]">
                        <template x-if="loading">
                            <div class="flex items-center justify-center h-full">
                                <span class="w-6 h-6 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin"></span>
                            </div>
                        </template>

                        <template x-for="msg in messages" :key="msg.id">
                            <div class="flex" :class="msg.sender_id == {{ Auth::id() }} ? 'justify-end' : 'justify-start'">
                                <div class="max-w-[70%] p-3.5 rounded-2xl text-sm shadow-sm"
                                     :class="msg.sender_id == {{ Auth::id() }} ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-slate-100 dark:bg-gray-700 text-slate-800 dark:text-slate-100 rounded-bl-none'">
                                    <p class="leading-relaxed" x-text="msg.content"></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Input Box -->
                    <div class="p-4 border-t dark:border-gray-700 bg-slate-50 dark:bg-gray-900/20">
                        <form @submit.prevent="sendMessage()" class="flex gap-2">
                            <input type="text" x-model="content" placeholder="Écrire un message..." class="w-full border rounded-xl p-2.5 bg-white dark:bg-gray-900 dark:text-white border-gray-200 dark:border-gray-700 focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 text-white px-5 rounded-xl font-semibold shadow transition-all">
                                Envoyer
                            </button>
                        </form>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-full text-center text-gray-400 py-12">
                        <svg class="w-16 h-16 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <p class="font-medium">Sélectionnez une discussion</p>
                        <p class="text-xs mt-1">Choisissez un contact dans la barre latérale pour démarrer.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
