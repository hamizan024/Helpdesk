<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name'       => ['required', 'string', 'max:50', Rule::unique('statuses', 'name')->ignore($this->route('status'))],
            'color'      => ['required', 'string', 'in:primary,secondary,success,danger,warning,info,dark'],
            'is_default' => ['boolean'],
            'is_active'  => ['boolean'],
        ];
    }
}