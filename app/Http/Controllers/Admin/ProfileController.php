<?php

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\Profile\UpdateProfileDTO;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Services\ProfileService; // Added
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use  App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function __construct(protected ProfileService $profileService) // Added
    {}

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->profileService->updateProfile($request->user(),UpdateProfileDTO::fromArray($request->validated()));

        return Redirect::route('admin.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $this->profileService->deleteAccount($request->user(), $request); // Modified

        return Redirect::to('/');
    }
}
