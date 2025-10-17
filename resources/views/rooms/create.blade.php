<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create a new chat room
            </h2>

            <a href="{{ route('rooms.index') }}"
               class="bg-[#b5d54b] text-white px-5 py-2 rounded-lg hover:bg-[#f37ba0] hover:text-white transition">
               Back to Rooms
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-[#FDFDFC] min-h-screen flex justify-center items-start">
        <div class="max-w-2xl w-full sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-2xl p-8">
                <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-gray-800 font-semibold mb-2">Room Name</label>
                        <x-input type="text" name="name" id="name" placeholder="Enter room name"
                                 class="w-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#ac88cb]" required />
                    </div>

                    <div>
                        <label for="avatar" class="block text-gray-800 font-semibold mb-2">Avatar (optional)</label>
                        <input type="file" name="avatar" id="avatar"
                               class="w-full file:border file:border-gray-300 file:rounded-lg file:px-4 file:py-2 file:bg-gray-100 file:text-gray-700 hover:file:bg-[#ac88cb] hover:file:text-white transition">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-[#ac88cb] text-white px-6 py-2 rounded-lg hover:bg-[#9adde6] transition font-semibold">
                            Create Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
