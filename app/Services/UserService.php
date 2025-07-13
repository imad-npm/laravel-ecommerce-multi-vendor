<?php

namespace App\Services;

use App\DataTransferObjects\UserData;
use App\Models\User;
use Illuminate\Http\Request;

class UserService
{
    public function getUsers(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');

        return User::query()
            ->when($search, function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($role, function($q) use ($role) {
                $q->where('role', $role);
            })
            ->latest()->paginate(15)->withQueryString();
    }

    public function createUser(UserData $data): User
    {
        return User::create($data->toArray());
    }

    public function updateUser(User $user, UserData $data): bool
    {
        return $user->update($data->toArray());
    }

    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }
}
            