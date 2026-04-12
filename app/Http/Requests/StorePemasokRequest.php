<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemasokRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_pemasok' => ['required', 'string', 'max:100'],
            'kontak_person' => ['nullable', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'email' => ['nullable', 'email', 'max:150'],
            'is_active' => ['boolean'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nama_pemasok' => 'nama pemasok',
            'kontak_person' => 'kontak person',
        ];
    }
}