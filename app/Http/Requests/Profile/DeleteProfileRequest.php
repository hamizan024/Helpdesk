<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the password confirmation required before deleting the user's account.
 *
 * Uses the 'userDeletion' error bag to match the named bag referenced in
 * the delete-user-form view ($errors->userDeletion).
 */
class DeleteProfileRequest extends FormRequest
{
    protected $errorBag = 'userDeletion';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'current_password'],
        ];
    }
}
