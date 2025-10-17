<x-app-layout>
    @vite(['resources/js/app.js'])

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Direct Messages</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                
                @if($usersWithMessages->count() > 0)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Conversations</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($usersWithMessages as $user)
                        @php
                            $unreadCount = auth()->user()->unreadMessagesFrom($user->id);
                            $lastMessage = \App\Models\Message::where(function($q) use ($user) {
                                $q->where('user_id', auth()->id())->where('to_user_id', $user->id);
                            })->orWhere(function($q) use ($user) {
                                $q->where('user_id', $user->id)->where('to_user_id', auth()->id());
                            })->latest()->first();
                        @endphp
                        <a href="{{ route('direct.show', $user) }}" 
                           class="flex items-center space-x-4 p-4 border rounded-lg hover:bg-gray-50 transition relative">
                            <div class="relative">
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" 
                                     class="w-12 h-12 rounded-full">
                                @if($unreadCount > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold">
                                    {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                </span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900 truncate">{{ $user->name }}</h4>
                                    @if($user->role === 'admin')
                                    <span class="text-xs text-white bg-[#ac88cb] px-2 py-0.5 rounded-full">Admin</span>
                                    @endif
                                </div>
                                @if($lastMessage)
                                <p class="text-sm text-gray-500 truncate">
                                    {{ $lastMessage->user_id === auth()->id() ? 'You: ' : '' }}
                                    {{ $lastMessage->body ? Str::limit($lastMessage->body, 40) : '📎 Attachment' }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $lastMessage->created_at->diffForHumans() }}
                                </p>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">All Users</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($allUsers as $user)
                        <a href="{{ route('direct.show', $user) }}" 
                           class="flex items-center space-x-4 p-4 border rounded-lg hover:bg-gray-50 transition">
                            <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" 
                                 class="w-12 h-12 rounded-full">
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $user->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                @if($user->role === 'admin')
                                <span class="text-xs text-white bg-[#ac88cb] px-2 py-0.5 rounded-full mt-1 inline-block">Admin</span>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>