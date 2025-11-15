      @php
                  use App\Enums\UserRole;
              @endphp

<x-app-layout>
    <x-slot name="header">
      <h2 class="text-2xl font-bold">Edit User #{{ $user->id }}</h2>
    </x-slot>
  
    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 rounded shadow">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
          @csrf @method('PATCH')
  
          <div class="mb-4">
            <label class="block text-sm font-medium">Name</label>
            <input name="name" value="{{ old('name', $user->name) }}" required class="mt-1 w-full border-gray-300 rounded p-2">
            @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
          </div>
  
          <div class="mb-4">
            <label class="block text-sm font-medium">Email</label>
            <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="mt-1 w-full border-gray-300 rounded p-2">
            @error('email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
          </div>
  
          <div class="mb-4">
            <label class="block text-sm font-medium">Role</label>
            <select name="role" required class="mt-1 w-full border-gray-300 rounded p-2">
        
              @foreach(UserRole::cases() as $role)
                <option value="{{ $role->value }}" {{ old('role', $user->role->value) === $role->value ? 'selected' : '' }}>
                  {{ ucfirst($role->value) }}
                </option>
              @endforeach
            </select>
            @error('role')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
          </div>
  
          <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
            Update User
          </button>
        </form>
      </div>
    </div>
  </x-app-layout>
  