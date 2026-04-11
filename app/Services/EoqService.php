<?php

namespace App\Services;

use App\Models\EoqSetting;

class EoqService
{
    /**
     * Hitung EOQ dan Reorder Point.
     *
     * Q* = sqrt((2 * D * S) / H)
     * ROP = (D / 365) * lead_time_hari
     */
    public function hitung(float $D, float $S, float $H, int $leadTimeDays = 1): array
    {
        $eoq = ($H > 0) ? sqrt((2 * $D * $S) / $H) : 0;
        $rop = ($D / 365) * $leadTimeDays;

        return [
            'eoq_result' => round($eoq, 2),
            'reorder_point' => round($rop, 2),
        ];
    }

    public function hitungDanSimpan(EoqSetting $setting): EoqSetting
    {
        $hasil = $this->hitung(
            $setting->permintaan_tahunan,
            $setting->biaya_pemesanan,
            $setting->biaya_penyimpanan,
            $setting->lead_time_hari
        );

        $setting->eoq_result = $hasil['eoq_result'];
        $setting->reorder_point = $hasil['reorder_point'];
        $setting->save();

        return $setting;
    }

    public function perluDipesan(float $stokSaatIni, float $reorderPoint): bool
    {
        return $stokSaatIni <= $reorderPoint;
    }
}