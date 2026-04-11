<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BahanBaku;
use App\Models\EoqSetting;
use App\Services\EoqService;

class EoqSettingSeeder extends Seeder
{
    /**
     * Rumus EOQ  : Q* = sqrt((2 * D * S) / H)
     * Rumus ROP  : ROP = (D / 365) * lead_time_hari
     *
     * D = permintaan tahunan (satuan/tahun)
     * S = biaya pemesanan per sekali pesan (Rp)
     * H = biaya penyimpanan per satuan per tahun (Rp)
     */
    public function run(): void
    {
        $eoqService = new EoqService();

        $settings = [
            'BB-001' => [
                'permintaan_tahunan' => 3600,
                'biaya_pemesanan' => 25000,
                'biaya_penyimpanan' => 2700,
                'lead_time_hari' => 2,
            ],
            'BB-002' => [
                'permintaan_tahunan' => 1800,
                'biaya_pemesanan' => 25000,
                'biaya_penyimpanan' => 2400,
                'lead_time_hari' => 2,
            ],
            'BB-004' => [
                'permintaan_tahunan' => 1200,
                'biaya_pemesanan' => 20000,
                'biaya_penyimpanan' => 3000,
                'lead_time_hari' => 1,
            ],
            'BB-006' => [
                'permintaan_tahunan' => 720,
                'biaya_pemesanan' => 20000,
                'biaya_penyimpanan' => 4800,
                'lead_time_hari' => 2,
            ],
            'BB-009' => [
                'permintaan_tahunan' => 600,
                'biaya_pemesanan' => 15000,
                'biaya_penyimpanan' => 5600,
                'lead_time_hari' => 1,
            ],
            'BB-010' => [
                'permintaan_tahunan' => 240,
                'biaya_pemesanan' => 20000,
                'biaya_penyimpanan' => 15000,
                'lead_time_hari' => 3,
            ],
            'BB-012' => [
                'permintaan_tahunan' => 120,
                'biaya_pemesanan' => 20000,
                'biaya_penyimpanan' => 24000,
                'lead_time_hari' => 3,
            ],
            'BB-020' => [
                'permintaan_tahunan' => 60000,
                'biaya_pemesanan' => 15000,
                'biaya_penyimpanan' => 70,
                'lead_time_hari' => 2,
            ],
        ];

        foreach ($settings as $kode => $params) {
            $bahanBaku = BahanBaku::where('kode_bahan', $kode)->first();

            if (!$bahanBaku) {
                continue;
            }

            $hasil = $eoqService->hitung(
                $params['permintaan_tahunan'],
                $params['biaya_pemesanan'],
                $params['biaya_penyimpanan'],
                $params['lead_time_hari']
            );

            EoqSetting::updateOrCreate(
                ['bahan_baku_id' => $bahanBaku->id],
                [
                    'permintaan_tahunan' => $params['permintaan_tahunan'],
                    'biaya_pemesanan' => $params['biaya_pemesanan'],
                    'biaya_penyimpanan' => $params['biaya_penyimpanan'],
                    'lead_time_hari' => $params['lead_time_hari'],
                    'eoq_result' => $hasil['eoq_result'],
                    'reorder_point' => $hasil['reorder_point'],
                ]
            );
        }
    }
}