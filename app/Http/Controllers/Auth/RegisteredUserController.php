<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed'],
            'role' => ['required', Rule::in(array_column(UserRole::cases(), 'value'))],
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
        event(new Registered($user)); // ← OBLIGATOIRE pour envoyer l'email

        Auth::login($user);
    
        return redirect($this->redirectUserByRole($user));
    }
    
    private function redirectUserByRole($user): string
    {
        return match ($user->role) {
            UserRole::ADMIN => route('admin.dashboard'),
            UserRole::VENDOR => route('vendor.dashboard'),
            UserRole::CUSTOMER => route('customer.home'),
            default => '/',
        };
    }
    
}
