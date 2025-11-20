<x-app-layout>
    <x-slot name="header">
      <h2 class="text-2xl font-bold">User #{{ $user->id }}</h2>
    </x-slot>
  
    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 rounded shadow space-y-4">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
        <p><strong>Joined:</strong> {{ $user->created_at->format('Y-m-d') }}</p>
  
        <div class="mt-4 flex space-x-4">
          <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Edit</a>
          <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-300 text-primary rounded hover:bg-gray-400">Back to list</a>
        </div>
      </div>
    </div>
  </x-app-layout>
  