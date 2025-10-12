<x-guest-layout>
    <x-authentication-card class="bg-white p-10 rounded-2xl shadow-lg max-w-md mx-auto">
        <x-slot name="logo" class="mb-6">
            <img src="{{ asset('images/yapping.png') }}" alt="Yapping Logo" class="mx-auto w-32 h-auto">
        </x-slot>

        <x-validation-errors class="mb-4 text-red-500" />

        <form method="POST" action="{{ route('register') }}">
            @csrf
            

            <div class="mb-4">
                <x-label for="name" value="{{ __('Name') }}" class="text-gray-700 font-semibold"/>
                <x-input id="name" class="mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"/>
            </div>

            <div class="mb-4">
                <x-label for="email" value="{{ __('Email') }}" class="text-gray-700 font-semibold"/>
                <x-input id="email" class="mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username"/>
            </div>

            <div class="mb-4">
                <x-label for="password" value="{{ __('Password') }}" class="text-gray-700 font-semibold"/>
                <x-input id="password" class="mt-1 w-full" type="password" name="password" required autocomplete="new-password"/>
            </div>

            <div class="mb-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="text-gray-700 font-semibold"/>
                <x-input id="password_confirmation" class="mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password"/>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mb-4">
                    <label for="terms" class="flex items-center">
                        <x-checkbox name="terms" id="terms" class="text-[#ac88cb]" required/>
                        <span class="ms-2 text-sm text-gray-600">
                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-[#9adde6] hover:text-[#ac88cb] rounded-md">'.__('Terms of Service').'</a>',
                                'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-[#f37ba0] hover:text-[#ac88cb] rounded-md">'.__('Privacy Policy').'</a>',
                            ]) !!}
                        </span>
                    </label>
                </div>
            @endif

            <div class="flex flex-col md:flex-row items-center justify-between gap-3 mt-4">
                <a class="underline text-sm text-[#9adde6] hover:text-[#ac88cb] transition" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="w-full md:w-auto bg-[#ac88cb] text-white px-6 py-2 rounded-lg hover:bg-[#9adde6] hover:text-[#1b1b18] transition">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
