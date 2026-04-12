<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePembelianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pemasok_id' => ['required', 'exists:pemasoks,id'],
            'tanggal_pembelian' => ['required', 'date', 'before_or_equal:today'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.bahan_baku_id' => ['required', 'exists:bahan_bakus,id'],
            'items.*.jumlah' => ['required', 'numeric', 'min:0.01'],
            'items.*.harga_satuan' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'pemasok_id' => 'pemasok',
            'tanggal_pembelian' => 'tanggal pembelian',
            'items.*.bahan_baku_id' => 'bahan baku',
            'items.*.jumlah' => 'jumlah',
            'items.*.harga_satuan' => 'harga satuan',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Minimal satu bahan baku harus ditambahkan.',
            'items.min' => 'Minimal satu bahan baku harus ditambahkan.',
        ];
    }
}