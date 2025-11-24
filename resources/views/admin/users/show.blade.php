<x-app-layout>
    <x-slot name="header">
      <h2 class="text-2xl font-bold">User #{{ $user->id }}</h2>
    </x-slot>
  
    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white p-6 rounded shadow space-y-4">
        <p><strong>Name:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Role:</strong> {{ ucfirst($user->role->value) }}</p>
        <p><strong>Joined:</strong> {{ $user->created_at->format('Y-m-d') }}</p>
  
        <div class="mt-4 flex space-x-4">
          <x-ui.button :href="route('admin.users.edit', $user)" variant="primary">
            <x-heroicon-o-pencil class="w-5 h-5 mr-2" />
            Edit
          </x-ui.button>
          <x-ui.button :href="route('admin.users.index')" variant="outline">
            <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
            Back to list
          </x-ui.button>
        </div>
      </div>
    </div>
  </x-app-layout>
  