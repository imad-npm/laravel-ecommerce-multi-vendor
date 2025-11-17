   @php
        use App\Enums\UserRole;
    @endphp
    <x-guest-layout>
 
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-ui.input-label for="name" :value="__('Name')" />
            <x-ui.input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-ui.input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-ui.input-label for="email" :value="__('Email')" />
            <x-ui.input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-ui.input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-ui.input-label for="password" :value="__('Password')" />

            <x-ui.input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-ui.input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-ui.input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-ui.input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-ui.input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

       <!-- Choix du rôle -->
<div class="mt-4">
    <x-ui.input-label for="role" value="You Are ?" />

    @php
        $roles = [
            ['value' => UserRole::CUSTOMER->value, 'label' => 'Customer'],
            ['value' => UserRole::VENDOR->value, 'label' => 'Vendor'],
        ];
    @endphp
    <x-ui.select-dropdown id="role" name="role" :options="$roles" :selected="old('role')" required class="mt-1 w-full" />

    <x-ui.input-error :messages="$errors->get('role')" class="mt-2" />
</div>


        <div class="flex items-center justify-end mt-4">
            <x-ui.link variant="default" class="underline text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
    {{ __('Already registered?') }}
</x-ui.link>

            <x-ui.button variant="primary" type="submit" class="ms-4">
                {{ __('Register') }}
            </x-ui.button>
        </div>
    </form>
</x-guest-layout>
