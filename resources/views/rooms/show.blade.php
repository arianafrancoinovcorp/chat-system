<x-app-layout>

    @vite(['resources/js/app.js'])

    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800">{{ $room->name }}</h2>

            @if(auth()->user()->role === 'admin')
            <!-- Botão Invite User -->
            <a href="#"
                id="inviteUserBtn"
                class="bg-[#b5d54b] text-white px-4 py-2 rounded-lg hover:bg-[#f37ba0] transition font-semibold">
                Invite User
            </a>

            <!-- Invite User Modal -->
            <div id="inviteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
                <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
                    <h2 class="text-lg font-semibold mb-4">Invite a User to this Room</h2>
                    <form id="inviteForm">
                        <label for="user_email" class="block text-sm font-medium text-gray-700 mb-2">User Email</label>
                        <input type="email" id="user_email" name="email" placeholder="Enter user email"
                            class="w-full border-gray-300 rounded-lg p-2 mb-4 focus:ring-[#ac88cb] focus:border-[#ac88cb]" required>

                        <div class="flex justify-end space-x-2">
                            <button type="button" id="cancelInvite"
                                class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">Cancel</button>
                            <button type="submit"
                                class="bg-[#ac88cb] text-white px-4 py-2 rounded-lg hover:bg-[#9adde6] transition font-semibold">
                                Send Invite
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

        </div>
    </x-slot>

    <div class="flex h-[80vh] bg-[#FDFDFC] rounded-2xl shadow-lg overflow-hidden mt-6 max-w-7xl mx-auto">
        <!-- Sidebar: Members -->
        <div class="w-1/4 bg-gray-50 p-4 border-r overflow-y-auto">
            <h3 class="font-semibold mb-4 text-gray-700">Members</h3>
            <ul class="space-y-2">
                @foreach($room->users as $user)
                <li>
                    @if($user->id !== auth()->id())
                    <a href="{{ route('direct.show', $user) }}"
                        class="flex justify-between items-center p-2 rounded-lg hover:bg-gray-100 transition cursor-pointer group">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                class="w-8 h-8 rounded-full">
                            <div>
                                <div class="font-medium">{{ $user->name }}</div>
                                @if($user->role === 'admin')
                                <span class="text-xs text-gray-500">Admin</span>
                                @endif
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-[#ac88cb] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </a>
                    @else
                    <div class="flex justify-between items-center p-2 rounded-lg bg-gray-100">
                        <div class="flex items-center space-x-3">
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}"
                                class="w-8 h-8 rounded-full">
                            <div>
                                <div class="font-medium">{{ $user->name }}</div>
                                <span class="text-xs text-gray-500">You</span>
                            </div>
                        </div>
                        @if($user->role === 'admin')
                        <span class="text-xs text-white bg-[#ac88cb] px-2 py-0.5 rounded-full">Admin</span>
                        @endif
                    </div>
                    @endif
                </li>
                @endforeach
            </ul>

            <!-- Link para ver todas as DMs -->
            <div class="mt-6 pt-6 border-t">
                <a href="{{ route('direct.index') }}" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 transition text-[#ac88cb] font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>All Direct Messages</span>
                </a>
            </div>
        </div>

        <!-- Chat area -->
        <div class="flex-1 flex flex-col">
            <!-- Messages -->
            <div id="messages" class="flex-1 p-4 overflow-y-auto space-y-2">
                @foreach($room->messages as $message)
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

            <!-- Input -->
            <form id="message-form" class="p-4 border-t flex space-x-2 bg-white">
                <x-input type="text" id="message-input" placeholder="Type a message..."
                    class="flex-1 focus:ring-2 focus:ring-[#ac88cb] rounded-lg" />
                <button type="submit" class="bg-[#ac88cb] text-white px-4 py-2 rounded-lg hover:bg-[#9adde6] transition font-semibold">
                    Send
                </button>
            </form>
        </div>
    </div>
    @vite(['resources/js/app.js'])

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Page was loaded!');

            const authId = @json(auth()->id());
            const roomId = @json($room->id);

            const messagesDiv = document.getElementById('messages');
            const form = document.getElementById('message-form');
            const input = document.getElementById('message-input');

            console.log('👤 Auth ID:', authId);
            console.log('🏠 Room ID:', roomId);
            console.log('📡 Echo', !!window.Echo);
            console.log('📡 Pusher', !!window.Pusher);

            function appendMessage(message, isOwn = false) {
                console.log('➕ Adding message:', message);
                const div = document.createElement('div');
                div.className = `p-2 rounded-lg max-w-[70%] ${isOwn ? 'bg-[#ac88cb] text-white ml-auto' : 'bg-gray-200'}`;
                div.innerHTML = `<strong>${message.user.name}:</strong> ${message.body}`;
                messagesDiv.appendChild(div);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }

            // Verification to see if Echo is available
            if (!window.Echo) {
                console.error('Echo not defined');
                alert('Error: Laravel Echo is not loaded. Verify your console.');
                return;
            }

            // Conection to private room
            console.log('🔌 Conecting to private room: room.' + roomId);

            const channel = window.Echo.private(`room.${roomId}`);

            channel
                .subscribed(() => {
                    console.log('✅ Subscibed to room chanel.' + roomId + ' sucssesfully');
                })
                .listen('.MessageSent', (e) => {
                    console.log('MESAGE RECIEVED VIA WEBSOCKET:', e);

                    if (!e.message) {
                        console.error('EVENT RECEVEID BUT THERE IS NO MESSAGE DATA', e);
                        return;
                    }

                    const msg = e.message;

                    if (!msg.user) {
                        console.error('⚠️ MESSAGE WITHOUT USER DETAILS', msg);
                        return;
                    }

                    const isOwn = msg.user.id === authId;
                    console.log('ONWED MESSAGE', isOwn);

                    appendMessage(msg, isOwn);
                })
                .error((error) => {
                    console.error('❌ ERROR ON CHANEL', error);

                    if (error.type === 'AuthError') {
                        console.error('Auth error --- channels');
                    }
                });

            console.log('Listener registed to MessageSent');

            // Sending message
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const body = input.value.trim();
                if (!body) return;

                console.log('Sending message', body);

                try {
                    const res = await fetch("{{ route('messages.store') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            body,
                            room_id: roomId
                        })
                    });

                    if (res.ok) {
                        const data = await res.json();
                        console.log('✅ Server response:', data);

                        //appendMessage(data, true);
                        input.value = '';
                    } else {
                        const errorText = await res.text();
                        console.error('❌ Error :', res.status, errorText);
                        alert('Error to send message');
                    }
                } catch (error) {
                    console.error('❌ Error on request', error);
                    alert('Conetion error.');
                }
            });
        });

        // === INVITE MODAL ===
        const inviteBtn = document.getElementById('inviteUserBtn');
        const inviteModal = document.getElementById('inviteModal');
        const cancelInvite = document.getElementById('cancelInvite');
        const inviteForm = document.getElementById('inviteForm');

        inviteBtn.addEventListener('click', (e) => {
            e.preventDefault();
            inviteModal.classList.remove('hidden');
            inviteModal.classList.add('flex');
        });

        cancelInvite.addEventListener('click', () => {
            inviteModal.classList.remove('flex');
            inviteModal.classList.add('hidden');
        });

        inviteForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('user_email').value.trim();
            if (!email) return alert('Please enter an email.');

            try {
                const res = await fetch("{{ route('rooms.invite', $room) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        email
                    })
                });

                if (res.ok) {
                    alert('✅ User invited successfully!');
                    inviteModal.classList.add('hidden');
                    inviteModal.classList.remove('flex');
                    document.getElementById('user_email').value = '';
                } else {
                    const err = await res.json();
                    alert('❌ ' + (err.message || 'Error inviting user.'));
                }
            } catch (error) {
                console.error(error);
                alert('❌ Connection error.');
            }
        });
    </script>
</x-app-layout>