<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'pembelian_id',
        'bahan_baku_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'harga_satuan' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function pembelian()
    {
        return $this->belongsTo(PembelianBahanBaku::class, 'pembelian_id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}