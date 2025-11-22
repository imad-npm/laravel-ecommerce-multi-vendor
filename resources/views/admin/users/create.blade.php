  @php
    use App\Enums\UserRole;
@endphp

<x-app-layout>
    <x-slot name="header">
      <h2 class="text-2xl font-bold">Create New User</h2>
    </x-slot>
  
    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 rounded shadow">
        <form method="POST" action="{{ route('admin.users.store') }}">
          @csrf
  
          <div class="mb-4">
            <x-ui.input-label for="name" value="Name" />
            <x-ui.input id="name" name="name" :value="old('name')" required class="mt-1 w-full" />
            <x-ui.input-error :messages="$errors->get('name')" class="mt-2" />
          </div>
  
          <div class="mb-4">
            <x-ui.input-label for="email" value="Email" />
            <x-ui.input id="email" type="email" name="email" :value="old('email')" required class="mt-1 w-full" />
            <x-ui.input-error :messages="$errors->get('email')" class="mt-2" />
          </div>
  
          <div class="mb-4">
            <x-ui.input-label for="role" value="Role" />
            @php
                $roles = [];
                foreach (UserRole::cases() as $role) {
                    $roles[] = ['value' => $role->value, 'label' => ucfirst($role->value)];
                }
            @endphp
            <x-ui.select-dropdown id="role" name="role" :options="$roles" :selected="old('role')" required class="mt-1 w-full" />
            <x-ui.input-error :messages="$errors->get('role')" class="mt-2" />
          </div>
  
          <div class="mb-4">
            <x-ui.input-label for="password" value="Password" />
            <x-ui.input id="password" type="password" name="password" required class="mt-1 w-full" />
            <x-ui.input-error :messages="$errors->get('password')" class="mt-2" />
          </div>
  
          <div class="mb-4">
            <x-ui.input-label for="password_confirmation" value="Confirm Password" />
            <x-ui.input id="password_confirmation" type="password" name="password_confirmation" required class="mt-1 w-full" />
          </div>
  
          <x-ui.button variant="primary" type="submit" class="w-full justify-center">
            Create User
          </x-ui.button>
        </form>
      </div>
    </div>
  </x-app-layout>
  