<?php

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\User\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserService $userService)
    {}

    public function index(Request $request)
    {
        $users = $this->userService->getUsers($request);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(CreateUserRequest $request)
    {
        $userData = UserDTO::fromRequest($request);
        $this->userService->createUser($userData);
        return redirect()->route('admin.users.index')->with('success','User created.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $userData = UserDTO::fromRequest($request);
        $this->userService->updateUser($user, $userData);
        return redirect()->route('admin.users.index')->with('success','User updated.');
    }

    public function destroy(User $user)
    {
        $this->userService->deleteUser($user);
        return redirect()->route('admin.users.index')->with('success','User deleted.');
    }
}