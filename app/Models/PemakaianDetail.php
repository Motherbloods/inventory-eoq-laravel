<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PemakaianDetail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'pemakaian_id',
        'bahan_baku_id',
        'jumlah',
    ];

    protected function casts(): array
    {
        return ['jumlah' => 'decimal:2'];
    }

    public function pemakaian()
    {
        return $this->belongsTo(PemakaianBahanBaku::class, 'pemakaian_id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}