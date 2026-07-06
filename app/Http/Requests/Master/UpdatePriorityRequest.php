<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePriorityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:50', Rule::unique('priorities', 'name')->ignore($this->route('priority'))],
            'color'     => ['required', 'string', 'in:primary,secondary,success,danger,warning,info,dark'],
            'level'     => ['required', 'integer', 'min:1', 'max:99', Rule::unique('priorities', 'level')->ignore($this->route('priority'))],
            'is_active' => ['boolean'],
        ];
    }
}