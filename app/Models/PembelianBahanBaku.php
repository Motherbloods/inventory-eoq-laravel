<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PembelianBahanBaku extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_transaksi',
        'tanggal_pembelian',
        'pemasok_id',
        'user_id',
        'total_harga',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_pembelian' => 'date',
            'total_harga' => 'decimal:2',
        ];
    }

    public static function generateNomor(): string
    {
        $prefix = 'PBB-' . now()->format('Ymd');
        $last = self::where('nomor_transaksi', 'like', $prefix . '%')
            ->orderByDesc('nomor_transaksi')
            ->value('nomor_transaksi');
        $urutan = $last ? (int) substr($last, -3) + 1 : 1;
        return $prefix . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail()
    {
        return $this->hasMany(PembelianDetail::class, 'pembelian_id');
    }
}