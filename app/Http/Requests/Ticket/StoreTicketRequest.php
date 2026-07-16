<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates the request payload for creating a new ticket.
 */
class StoreTicketRequest extends FormRequest
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
            'priority'    => ['nullable', 'in:Low,Medium,High'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'due_date'    => ['nullable', 'date', 'after_or_equal:today'],
            'attachments'          => ['nullable', 'array', 'max:5'],
            'attachments.*'        => ['file', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Judul tiket wajib diisi.',
            'description.required' => 'Deskripsi tiket wajib diisi.',
            'priority.in'          => 'Prioritas harus Low, Medium, atau High.',
            'category_id.exists'   => 'Kategori yang dipilih tidak ditemukan.',
            'due_date.after_or_equal' => 'Due date harus hari ini atau setelahnya.',
            'attachments.max'      => 'Maksimal 5 file attachment.',
            'attachments.*.max'    => 'Setiap file maksimal 10 MB.',
        ];
    }
}