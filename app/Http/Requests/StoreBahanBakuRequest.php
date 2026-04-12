<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBahanBakuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bahan = $this->route('bahan_baku');

        return [
            'kode_bahan' => [
                'required',
                'string',
                'max:20',
                Rule::unique('bahan_bakus', 'kode_bahan')->ignore($bahan?->id),
            ],
            'nama_bahan' => ['required', 'string', 'max:100'],
            'kategori' => ['required', 'string', 'max:50'],
            'satuan' => ['required', 'string', 'max:20'],
            'harga_satuan' => ['required', 'numeric', 'min:0'],
            'stok_minimum' => ['required', 'numeric', 'min:0'],
            'stok_saat_ini' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'kode_bahan' => 'kode bahan',
            'nama_bahan' => 'nama bahan',
            'harga_satuan' => 'harga satuan',
            'stok_minimum' => 'stok minimum',
            'stok_saat_ini' => 'stok saat ini',
        ];
    }
}