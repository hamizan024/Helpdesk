<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\DeleteProfileRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Manages the authenticated user's profile: viewing, updating, avatar, and account deletion.
 */
class ProfileController extends Controller
{
    public function __construct(private readonly UserService $userService)
    {
    }

    /**
     * Show the profile edit form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the authenticated user's profile information.
     *
     * Clears the email verification timestamp when the email address changes.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Upload or replace the authenticated user's avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
        ]);

        $this->userService->updateAvatar($request->user(), $request->file('avatar'));

        return Redirect::route('profile.edit')->with('status', 'avatar-updated');
    }

    /**
     * Remove the authenticated user's avatar.
     */
    public function destroyAvatar(Request $request): RedirectResponse
    {
        $this->userService->deleteAvatar($request->user());

        return Redirect::route('profile.edit')->with('status', 'avatar-removed');
    }

    /**
     * Delete the authenticated user's account after password confirmation.
     */
    public function destroy(DeleteProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}