<x-guest-layout>
    <!-- Session Status -->
    <x-ui.auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

<x-ui.button href="{{ route('google.redirect') }}"
  variant="outline" class="mb-2 w-full items-center">
    <x-icon.google  class="w-5 h-5 me-3" />
    Login with Google
</x-ui.button>

        <!-- Email Address -->
        <div>
            <x-ui.input-label for="email" :value="__('Email')" />
            <x-ui.input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-ui.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-ui.input-label for="password" :value="__('Password')" />

            <x-ui.input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-ui.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <x-ui.checkbox id="remember_me" name="remember" />
                <span class="ms-2 text-sm text-neutral-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <x-ui.link variant="default"
                    class="underline text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </x-ui.link>
            @endif

            <x-ui.button variant="primary" type="submit" class="ms-3">
                {{ __('Log in') }}
            </x-ui.button>
        </div>
    </form>
</x-guest-layout>
