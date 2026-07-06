<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class StorePriorityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:50', 'unique:priorities,name'],
            'color'     => ['required', 'string', 'in:primary,secondary,success,danger,warning,info,dark'],
            'level'     => ['required', 'integer', 'min:1', 'max:99', 'unique:priorities,level'],
            'is_active' => ['boolean'],
        ];
    }
}