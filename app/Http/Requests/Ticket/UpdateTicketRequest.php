<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the request payload for updating an existing ticket.
 */
class UpdateTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority'    => ['required', 'in:Low,Medium,High'],
            'status'      => ['required', 'in:Open,In Progress,Closed'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Judul tiket wajib diisi.',
            'description.required' => 'Deskripsi tiket wajib diisi.',
            'priority.in'          => 'Prioritas harus Low, Medium, atau High.',
            'status.in'            => 'Status harus Open, In Progress, atau Closed.',
            'assigned_to.exists'   => 'Teknisi yang dipilih tidak ditemukan.',
        ];
    }
}
