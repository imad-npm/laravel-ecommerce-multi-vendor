
@php
                use App\Enums\UserRole;
            @endphp
<x-app-layout>
    <x-slot name="header">
      <h2 class="text-2xl font-bold">Users</h2>
    </x-slot>
  
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-semibold text-primary">User List</h3>
          <a href="{{ route('admin.users.create') }}"
             class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
            + New User
          </a>
        </div>
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4 w-full max-w-2xl flex flex-wrap gap-2 items-center bg-gray-50 p-3 rounded shadow-sm">
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="flex-1 min-w-0 px-4 py-2 border border-gray-300 rounded-lg" />
          <select name="role" class="px-4 py-2 w-36 border border-gray-300 rounded-lg">
            
            <option value="">All Roles</option>
            <option value="{{ UserRole::ADMIN->value }}" {{ request('role') == UserRole::ADMIN->value ? 'selected' : '' }}>Admin</option>
            <option value="{{ UserRole::VENDOR->value }}" {{ request('role') == UserRole::VENDOR->value ? 'selected' : '' }}>Vendor</option>
            <option value="{{ UserRole::CUSTOMER->value }}" {{ request('role') == UserRole::CUSTOMER->value ? 'selected' : '' }}>Customer</option>
          </select>
          <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg"> Search</button>
        </form>
        <x-table.index>
            <x-table.head>
              <x-table.row>
                <x-table.header>ID</x-table.header>
                <x-table.header>Name</x-table.header>
                <x-table.header>Email</x-table.header>
                <x-table.header>Role</x-table.header>
                <x-table.header class="text-right">Actions</x-table.header>
              </x-table.row>
            </x-table.head>
            <x-table.body>
              @forelse($users as $user)
                <x-table.row>
                  <x-table.data>{{ $user->id }}</x-table.data>
                  <x-table.data>{{ $user->name }}</x-table.data>
                  <x-table.data>{{ $user->email }}</x-table.data>
                  <x-table.data>{{ ucfirst($user->role->value) }}</x-table.data>
                  <x-table.actions class="space-x-2">
                    <x-ui.link variant="primary" href="{{ route('admin.users.show', $user) }}">View</x-ui.link>
                    <x-ui.link variant="primary" href="{{ route('admin.users.edit', $user) }}">Edit</x-ui.link>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
                      @csrf @method('DELETE')
                      <x-ui.button variant="text" color="danger" type="submit">Delete</x-ui.button>
                    </form>
                  </x-table.actions>
                </x-table.row>
              @empty
                <x-table.empty>
                  No users found.
                </x-table.empty>
              @endforelse
            </x-table.body>
          </x-table.index>
      <div class="mt-6">
    {{ $users->links() }}
</div>

      </div>
    </div>
  </x-app-layout>
