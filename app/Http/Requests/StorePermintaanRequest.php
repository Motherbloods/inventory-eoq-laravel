<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePermintaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'catatan' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.bahan_baku_id' => ['required', 'exists:bahan_bakus,id', 'distinct'],
            'items.*.jumlah_diminta' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function attributes(): array
    {
        return [
            'items.*.bahan_baku_id' => 'bahan baku',
            'items.*.jumlah_diminta' => 'jumlah diminta',
        ];
    }

    public function messages(): array
    {
        return [
            'items.*.bahan_baku_id.distinct' => 'Bahan baku tidak boleh dipilih lebih dari satu kali.',
            'items.required' => 'Minimal satu bahan baku harus ditambahkan.',
        ];
    }
}