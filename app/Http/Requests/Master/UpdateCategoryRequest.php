<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($this->route('category'))],
            'description'   => ['nullable', 'string', 'max:500'],
            'department_id'    => ['nullable', 'exists:departments,id'],
            'default_priority' => ['nullable', 'in:Low,Medium,High'],
            'is_active'        => ['boolean'],
        ];
    }
}