<x-app-layout>
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg shadow-md">
        {{ session('success') }}
    </div>
    @endif
    <x-slot name="header">
        <div class="w-full flex justify-between items-center">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Chat Rooms
            </h2>

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('rooms.create') }}"
                class="bg-[#ac88cb] text-white px-6 py-2 rounded-lg hover:bg-[#9adde6] transition">
                Create New Chat
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($rooms as $room)
                <a href="{{ route('rooms.show', $room) }}" class="block">
                    <div class="card bg-white shadow-md hover:shadow-lg transition p-4 rounded-lg cursor-pointer">
                        <div class="flex items-center space-x-4">
                            @if($room->avatar)
                            <img src="{{ asset('storage/' . $room->avatar) }}" alt="{{ $room->name }}" class="w-12 h-12 rounded-full object-cover">
                            @else
                            <div class="w-12 h-12 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($room->name, 0, 1)) }}
                            </div>
                            @endif
                            <div>
                                <h2 class="text-lg font-semibold">{{ $room->name }}</h2>
                                <p class="text-sm text-gray-500">{{ $room->users->count() }} member(s)</p>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>