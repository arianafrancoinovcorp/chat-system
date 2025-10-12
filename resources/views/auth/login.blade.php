<x-guest-layout>
    <x-authentication-card class="bg-white p-10 rounded-2xl shadow-lg max-w-md mx-auto">
        <x-slot name="logo" class="mb-6">
        <img src="{{ asset('images/yapping.png') }}" alt="Yapping Logo" class="mx-auto w-32 h-auto">
        </x-slot>

        <x-validation-errors class="mb-4 text-red-500" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <x-label for="email" value="{{ __('Email') }}" class="text-gray-700 font-semibold" />
                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />

            </div>

            <div class="mb-4">
                <x-label for="password" value="{{ __('Password') }}" class="text-gray-700 font-semibold" />
                <x-input id="password" class="block mt-1 w-full border-gray-300 focus:border-[#ac88cb] focus:ring focus:ring-[#ac88cb]/50 rounded-lg shadow-sm" 
                         type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class="flex items-center mb-6">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" class="text-[#ac88cb]" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-3">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-[#9adde6] hover:text-[#ac88cb] rounded-md transition" 
                       href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-button class="w-full md:w-auto bg-[#ac88cb] text-white px-6 py-2 rounded-lg hover:bg-[#9adde6] hover:text-[#1b1b18] transition">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>

        <p class="mt-6 text-center text-gray-600">
            New here? 
            <a href="{{ route('register') }}" class="text-[#f37ba0] hover:text-[#ac88cb] font-semibold transition">
                Create an account
            </a>
        </p>
    </x-authentication-card>
</x-guest-layout>
