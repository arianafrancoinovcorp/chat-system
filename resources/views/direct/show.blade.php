<x-app-layout>
    @vite(['resources/js/app.js'])

    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <div class="flex items-center space-x-3">
                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="flex h-[80vh] bg-[#FDFDFC] rounded-2xl shadow-lg overflow-hidden mt-6 max-w-7xl mx-auto">
        <!-- Sidebar: Utilizadores -->
        <div class="w-1/4 bg-gray-50 p-4 border-r overflow-y-auto">
            <!-- Botão Voltar -->
            <a href="{{ route('direct.index') }}"
                class="flex items-center space-x-2 mb-4 p-2 rounded-lg hover:bg-gray-100 transition text-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span>All Messages</span>
            </a>

            <h3 class="font-semibold mb-4 text-gray-700">Direct Messages</h3>
            <ul class="space-y-2">
                @foreach(\App\Models\User::where('id', '!=', auth()->id())->get() as $u)
                @php
                $unreadCount = auth()->user()->unreadMessagesFrom($u->id);
                @endphp
                <li>
                    <a href="{{ route('direct.show', $u) }}"
                        class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition relative
                      {{ $u->id === $user->id ? 'bg-[#ac88cb] text-white hover:bg-[#ac88cb]' : '' }}">
                        <div class="relative">
                            <img src="{{ $u->profile_photo_url }}" alt="{{ $u->name }}" class="w-8 h-8 rounded-full">
                            @if($unreadCount > 0 && $u->id !== $user->id)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="font-medium">{{ $u->name }}</div>
                            @if($u->role === 'admin')
                            <span class="text-xs {{ $u->id === $user->id ? 'text-white' : 'text-gray-500' }}">Admin</span>
                            @endif
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>

            <div class="mt-6 pt-6 border-t">
                <h3 class="font-semibold mb-4 text-gray-700">Your Rooms</h3>
                <ul class="space-y-2">
                    @foreach(auth()->user()->rooms as $room)
                    <li>
                        <a href="{{ route('rooms.show', $room) }}"
                            class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 bg-[#b5d54b] rounded-lg flex items-center justify-center text-white font-bold">
                                {{ substr($room->name, 0, 1) }}
                            </div>
                            <span>{{ $room->name }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Chat area -->
        <div class="flex-1 flex flex-col">
            <!-- Mensagens -->
            <div id="messages" class="flex-1 p-4 overflow-y-auto space-y-2">
                @foreach($messages as $message)
                <div class="p-2 rounded-lg max-w-[70%] 
                        @if($message->user_id === auth()->id())
                            bg-[#ac88cb] text-white ml-auto
                        @else
                            bg-gray-200
                        @endif">
                    <strong>{{ $message->user->name }}:</strong> {{ $message->body }}
                </div>
                @endforeach
            </div>

            <!-- Input de mensagem -->
            <form id="message-form" class="p-4 border-t flex space-x-2 bg-white">
                <x-input type="text" id="message-input" placeholder="Type a message to {{ $user->name }}..."
                    class="flex-1 focus:ring-2 focus:ring-[#ac88cb] rounded-lg" />
                <button type="submit" class="bg-[#ac88cb] text-white px-4 py-2 rounded-lg hover:bg-[#9adde6] transition font-semibold">
                    Send
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Dm page loaded');

            const authId = @json(auth()->id());
            const otherUserId = @json($user->id);

            const messagesDiv = document.getElementById('messages');
            const form = document.getElementById('message-form');
            const input = document.getElementById('message-input');

            console.log('👤 Auth ID:', authId);
            console.log('👥 Other User ID:', otherUserId);

            function appendMessage(message, isOwn = false) {
                console.log('➕ Adicionando mensagem:', message);
                const div = document.createElement('div');
                div.className = `p-2 rounded-lg max-w-[70%] ${isOwn ? 'bg-[#ac88cb] text-white ml-auto' : 'bg-gray-200'}`;
                div.innerHTML = `<strong>${message.user.name}:</strong> ${message.body}`;
                messagesDiv.appendChild(div);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }

            if (!window.Echo) {
                console.error('❌ Echo not defined');
                return;
            }
            console.log('🔌 conecting to private channel with: user.' + authId);

            window.Echo.private(`user.${authId}`)
                .subscribed(() => {
                    console.log('✅ user channel.' + authId);
                })
                .listen('.DirectMessageSent', (e) => {
                    console.log('dm recived:', e);

                    if (!e.message || !e.message.user) return;

                    const msg = e.message;

                    if (msg.user_id === otherUserId) {
                        appendMessage(msg, false);
                    }
                })
                .error((error) => {
                    console.error('❌ channel error:', error);
                });

            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const body = input.value.trim();
                if (!body) return;

                console.log('sending direct message:', body);

                try {
                    const res = await fetch("{{ route('messages.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            body: body,
                            to_user_id: otherUserId
                        })
                    });

                    if (res.ok) {
                        const data = await res.json();
                        console.log('✅ message sent:', data);
                        appendMessage(data, true);
                        input.value = '';
                    } else {
                        console.error('❌ Error:', await res.text());
                    }
                } catch (error) {
                    console.error('❌ Error:', error);
                }
            });
        });
    </script>

</x-app-layout>