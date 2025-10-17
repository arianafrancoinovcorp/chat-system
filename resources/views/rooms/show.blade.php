<x-app-layout>

@vite(['resources/js/app.js'])

    <x-slot name="header">
        <div class="flex justify-between items-center w-full">
            <h2 class="font-semibold text-xl text-gray-800">{{ $room->name }}</h2>

            @if(auth()->user()->role === 'admin')
            <a href="#"
                class="bg-[#b5d54b] text-white px-4 py-2 rounded-lg hover:bg-[#f37ba0] transition font-semibold">
                Invite User
            </a>
            @endif
        </div>
    </x-slot>

    <div class="flex h-[80vh] bg-[#FDFDFC] rounded-2xl shadow-lg overflow-hidden mt-6 max-w-7xl mx-auto">
        <!-- Sidebar: Members of the room -->
        <div class="w-1/4 bg-gray-50 p-4 border-r overflow-y-auto">
            <h3 class="font-semibold mb-4 text-gray-700">Members</h3>
            <ul class="space-y-2">
                @foreach($room->users as $user)
                <li class="flex justify-between items-center p-2 rounded-lg hover:bg-gray-100 transition">
                    <span>{{ $user->name }}</span>
                    @if($user->role === 'admin')
                    <span class="text-xs text-white bg-[#ac88cb] px-2 py-0.5 rounded-full">Admin</span>
                    @endif
                </li>
                @endforeach
            </ul>
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
                body: JSON.stringify({ body, room_id: roomId })
            });

            if (res.ok) {
                const data = await res.json();
                console.log('✅ Server response:', data);
                
                appendMessage(data, true);
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
</script>
</x-app-layout>
