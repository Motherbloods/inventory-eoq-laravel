<?php

namespace App\Services;

use App\Models\EoqSetting;

class EoqService
{
    /**
     * Tabel Z-score berdasarkan service level (%).
     * Nilai z dari distribusi normal standar one-tail.
     */
    private const Z_SCORES = [
        80 => 0.84,
        85 => 1.04,
        90 => 1.28,
        95 => 1.65,
        97 => 1.88,
        98 => 2.05,
        99 => 2.33,
    ];

    /**
     * Ambil Z-score dari service level.
     * Jika service level tidak ada di tabel, cari yang terdekat di bawahnya.
     */
    public function zScore(int $serviceLevel): float
    {
        if (isset(self::Z_SCORES[$serviceLevel])) {
            return self::Z_SCORES[$serviceLevel];
        }

        // Cari nilai terdekat di bawah
        $available = array_keys(self::Z_SCORES);
        $lower = array_filter($available, fn($v) => $v <= $serviceLevel);

        if (empty($lower)) {
            return self::Z_SCORES[80]; // fallback minimum
        }

        return self::Z_SCORES[max($lower)];
    }

    /**
     * Hitung EOQ, Safety Stock, dan Reorder Point.
     *
     * Q*          = sqrt((2 × D × S) / H)
     * Safety Stock = Z × σ × √(lead_time_hari)
     * ROP         = (D / 365) × lead_time_hari + Safety Stock
     *
     * @param float $D             Permintaan tahunan
     * @param float $S             Biaya pemesanan per order
     * @param float $H             Biaya penyimpanan per satuan per tahun
     * @param int   $leadTimeDays  Lead time dalam hari
     * @param int   $serviceLevel  Tingkat layanan dalam persen (mis. 95)
     * @param float $stdDev        Standar deviasi permintaan harian (σ)
     */
    public function hitung(
        float $D,
        float $S,
        float $H,
        int $leadTimeDays = 1,
        int $serviceLevel = 95,
        float $stdDev = 0.0
    ): array {
        // EOQ
        $eoq = ($H > 0) ? sqrt((2 * $D * $S) / $H) : 0;

        // Safety Stock: Z × σ × √(lead_time)
        $z = $this->zScore($serviceLevel);
        $safetyStock = ($stdDev > 0) ? $z * $stdDev * sqrt($leadTimeDays) : 0;

        // ROP = permintaan selama lead time + safety stock
        $rop = ($D / 365) * $leadTimeDays + $safetyStock;

        return [
            'eoq_result' => round($eoq, 2),
            'safety_stock' => round($safetyStock, 2),
            'reorder_point' => round($rop, 2),
        ];
    }

    public function hitungDanSimpan(EoqSetting $setting): EoqSetting
    {
        $hasil = $this->hitung(
            (float) $setting->permintaan_tahunan,
            (float) $setting->biaya_pemesanan,
            (float) $setting->biaya_penyimpanan,
            (int) $setting->lead_time_hari,
            (int) ($setting->service_level ?? 95),
            (float) ($setting->std_dev_permintaan ?? 0),
        );

        $setting->eoq_result = $hasil['eoq_result'];
        $setting->safety_stock = $hasil['safety_stock'];
        $setting->reorder_point = $hasil['reorder_point'];
        $setting->save();

        return $setting;
    }

    public function perluDipesan(float $stokSaatIni, float $reorderPoint): bool
    {
        return $stokSaatIni <= $reorderPoint;
    }
}