<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEoqSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bahan_baku_id' => ['required', 'exists:bahan_bakus,id'],
            'permintaan_tahunan' => ['required', 'numeric', 'min:0.01'],
            'biaya_pemesanan' => ['required', 'numeric', 'min:0.01'],
            'biaya_penyimpanan' => ['required', 'numeric', 'min:0.01'],
            'lead_time_hari' => ['required', 'integer', 'min:1', 'max:365'],
            'service_level' => ['required', 'integer', 'in:80,85,90,95,97,98,99'],
            'std_dev_permintaan' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function attributes(): array
    {
        return [
            'bahan_baku_id' => 'bahan baku',
            'permintaan_tahunan' => 'permintaan tahunan (D)',
            'biaya_pemesanan' => 'biaya pemesanan (S)',
            'biaya_penyimpanan' => 'biaya penyimpanan (H)',
            'lead_time_hari' => 'lead time (hari)',
            'service_level' => 'tingkat layanan (service level)',
            'std_dev_permintaan' => 'standar deviasi permintaan (σ)',
        ];
    }
}