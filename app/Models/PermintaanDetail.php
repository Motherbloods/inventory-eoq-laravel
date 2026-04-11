<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanDetail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'permintaan_id',
        'bahan_baku_id',
        'jumlah_diminta',
        'jumlah_disetujui',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_diminta' => 'decimal:2',
            'jumlah_disetujui' => 'decimal:2',
        ];
    }

    public function permintaan()
    {
        return $this->belongsTo(PermintaanBahan::class, 'permintaan_id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}