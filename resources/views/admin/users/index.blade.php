<x-app-layout>
    <x-slot name="header">
      <h2 class="text-2xl font-bold">Users</h2>
    </x-slot>
  
    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-semibold text-gray-800">User List</h3>
          <a href="{{ route('admin.users.create') }}"
             class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
            + New User
          </a>
        </div>
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4 w-full max-w-2xl flex flex-wrap gap-2 items-center bg-gray-50 p-3 rounded shadow-sm">
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="flex-1 min-w-0 px-4 py-2 border border-gray-300 rounded-lg" />
          <select name="role" class="px-4 py-2 w-36 border border-gray-300 rounded-lg">
            @php
                use App\Enums\UserRole;
            @endphp
            <option value="">All Roles</option>
            <option value="{{ UserRole::ADMIN->value }}" {{ request('role') == UserRole::ADMIN->value ? 'selected' : '' }}>Admin</option>
            <option value="{{ UserRole::VENDOR->value }}" {{ request('role') == UserRole::VENDOR->value ? 'selected' : '' }}>Vendor</option>
            <option value="{{ UserRole::CUSTOMER->value }}" {{ request('role') == UserRole::CUSTOMER->value ? 'selected' : '' }}>Customer</option>
          </select>
          <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg"> Search</button>
        </form>
        <div class="overflow-x-auto">
          <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-50 border-b text-gray-600 uppercase text-xs">
              <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Role</th>
                <th class="px-4 py-3 text-right">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($users as $user)
                <tr class="border-b hover:bg-gray-50">
                  <td class="px-4 py-3">{{ $user->id }}</td>
                  <td class="px-4 py-3">{{ $user->name }}</td>
                  <td class="px-4 py-3">{{ $user->email }}</td>
                  <td class="px-4 py-3">{{ ucfirst($user->role->value) }}</td>
                  <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:underline">View</a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 hover:underline">Edit</a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Delete this user?')">
                      @csrf @method('DELETE')
                      <button type="submit" class="text-red-600 hover:underline">Delete</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="px-4 py-4 text-center text-gray-500">No users found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      <div class="mt-6">
    {{ $users->links() }}
</div>

      </div>
    </div>
  </x-app-layout>
