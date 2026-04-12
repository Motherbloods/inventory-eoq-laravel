<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemakaianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_pemakaian' => ['required', 'date', 'before_or_equal:today'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.bahan_baku_id' => ['required', 'exists:bahan_bakus,id', 'distinct'],
            'items.*.jumlah' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function attributes(): array
    {
        return [
            'tanggal_pemakaian' => 'tanggal pemakaian',
            'items.*.bahan_baku_id' => 'bahan baku',
            'items.*.jumlah' => 'jumlah',
        ];
    }

    public function messages(): array
    {
        return [
            'items.*.bahan_baku_id.distinct' => 'Bahan baku tidak boleh dipilih lebih dari satu kali. Gabungkan jumlahnya.',
            'items.required' => 'Minimal satu bahan baku harus ditambahkan.',
        ];
    }
}