<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKoreksiStokRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bahan_baku_id' => ['required', 'exists:bahan_bakus,id'],
            'tanggal_koreksi' => ['required', 'date', 'before_or_equal:today'],
            'jumlah_sesudah' => ['required', 'numeric', 'min:0'],
            'alasan' => ['required', 'string', 'min:10', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'bahan_baku_id' => 'bahan baku',
            'tanggal_koreksi' => 'tanggal koreksi',
            'jumlah_sesudah' => 'jumlah stok baru',
        ];
    }

    public function messages(): array
    {
        return [
            'alasan.min' => 'Alasan koreksi minimal 10 karakter.',
        ];
    }
}