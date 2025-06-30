<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\VendorProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use  App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function __construct(protected VendorProfileService $vendorProfileService)
    {}

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('vendor.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->vendorProfileService->updateProfile($request->user(), $request->toUserData());

        return Redirect::route('vendor.profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $this->vendorProfileService->deleteAccount($request->user(), $request);

        return Redirect::to('/');
    }
}
