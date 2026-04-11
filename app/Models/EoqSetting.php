<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EoqSetting extends Model
{
    protected $fillable = [
        'bahan_baku_id',
        'permintaan_tahunan',
        'biaya_pemesanan',
        'biaya_penyimpanan',
        'lead_time_hari',
        'eoq_result',
        'reorder_point',
    ];

    protected function casts(): array
    {
        return [
            'permintaan_tahunan' => 'decimal:2',
            'biaya_pemesanan' => 'decimal:2',
            'biaya_penyimpanan' => 'decimal:2',
            'eoq_result' => 'decimal:2',
            'reorder_point' => 'decimal:2',
        ];
    }

    public function perluDipesan(): bool
    {
        return $this->reorder_point !== null &&
            $this->bahanBaku->stok_saat_ini <= $this->reorder_point;
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}