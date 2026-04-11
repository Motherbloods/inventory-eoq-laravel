<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PemakaianBahanBaku extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_transaksi',
        'tanggal_pemakaian',
        'user_id',
        'keterangan',
    ];

    protected function casts(): array
    {
        return ['tanggal_pemakaian' => 'date'];
    }

    public static function generateNomor(): string
    {
        $prefix = 'PKB-' . now()->format('Ymd');
        $last = self::where('nomor_transaksi', 'like', $prefix . '%')
            ->orderByDesc('nomor_transaksi')
            ->value('nomor_transaksi');
        $urutan = $last ? (int) substr($last, -3) + 1 : 1;
        return $prefix . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail()
    {
        return $this->hasMany(PemakaianDetail::class, 'pemakaian_id');
    }
}