<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yapping</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-[#FDFDFC] flex items-center justify-center min-h-screen p-6">
    <div class="text-center max-w-md bg-white p-10 rounded-2xl shadow-lg">
        <!-- Logo -->
        <img src="{{ asset('images/yapping.png') }}" alt="Yapping Logo" class="mx-auto mb-6 w-32 h-auto">

        <!-- Welcome message -->
        <p class="text-gray-800 mb-4 text-xl font-semibold leading-relaxed">
            Welcome to <span class="text-[#ac88cb] font-bold">Yapping</span> — your new chat playground! 🗨️
        </p>
        <p class="text-gray-700 mb-6 text-lg">
            Dive into conversations, share ideas, laugh out loud, and mostly, yap your heart out! 💬✨
        </p>
        <p class="text-gray-600 mb-8 text-md italic">
            Start connecting with friends or make new ones. Adventure awaits!
        </p>
        <!-- Call to action -->
        <div class="flex justify-center gap-4">
            @if (Route::has('login'))
            @auth
            <a href="{{ url('/dashboard') }}"
                class="bg-[#ac88cb] text-white px-6 py-2 rounded-lg hover:bg-[#9adde6] transition">
                Dashboard
            </a>
            @else
            <a href="{{ route('login') }}"
                class="bg-[#9adde6] text-[#1b1b18] px-6 py-2 rounded-lg hover:bg-[#ac88cb] hover:text-white transition">
                Log in
            </a>
            @if (Route::has('register'))
            <a href="{{ route('register') }}"
                class="bg-[#ac88cb] text-white px-6 py-2 rounded-lg hover:bg-[#f37ba0] transition">
                Register
            </a>
            @endif
            @endauth
            @endif
        </div>
    </div>
</body>

</html>