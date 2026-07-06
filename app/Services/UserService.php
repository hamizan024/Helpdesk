<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Provides user-related queries and mutations used across the application.
 */
class UserService
{
    /**
     * Return all users with the technician role.
     */
    public function getTechnicians(): Collection
    {
        return User::where('role', 'technician')->get();
    }

    /**
     * Store a new avatar for the user, deleting any previous one.
     *
     * Stores under avatars/{user_id}/ on the public disk.
     */
    public function updateAvatar(User $user, UploadedFile $file): void
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $file->store("avatars/{$user->id}", 'public');
        $user->update(['avatar' => $path]);
    }

    /**
     * Remove the user's avatar from storage and clear the database column.
     */
    public function deleteAvatar(User $user): void
    {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }
    }
}