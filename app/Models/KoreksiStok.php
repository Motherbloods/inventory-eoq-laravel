<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KoreksiStok extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_transaksi',
        'tanggal_koreksi',
        'bahan_baku_id',
        'jumlah_sebelum',
        'jumlah_sesudah',
        'selisih',
        'alasan',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_koreksi' => 'date',
            'jumlah_sebelum' => 'decimal:2',
            'jumlah_sesudah' => 'decimal:2',
            'selisih' => 'decimal:2',
        ];
    }

    public static function generateNomor(): string
    {
        $prefix = 'KRK-' . now()->format('Ymd');
        $last = self::where('nomor_transaksi', 'like', $prefix . '%')
            ->orderByDesc('nomor_transaksi')
            ->value('nomor_transaksi');
        $urutan = $last ? (int) substr($last, -3) + 1 : 1;
        return $prefix . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}