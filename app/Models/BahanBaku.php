<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BahanBaku extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_bahan',
        'nama_bahan',
        'kategori',
        'satuan',
        'harga_satuan',
        'stok_minimum',
        'stok_saat_ini',
        'deskripsi',
    ];

    protected function casts(): array
    {
        return [
            'harga_satuan' => 'decimal:2',
            'stok_minimum' => 'decimal:2',
            'stok_saat_ini' => 'decimal:2',
        ];
    }

    public function isBawahMinimum(): bool
    {
        return $this->stok_saat_ini <= $this->stok_minimum;
    }

    public function tambahStok(float $jumlah): void
    {
        $this->increment('stok_saat_ini', $jumlah);
    }

    public function kurangiStok(float $jumlah): void
    {
        if ($this->stok_saat_ini < $jumlah) {
            throw new \Exception("Stok {$this->nama_bahan} tidak mencukupi. Stok tersedia: {$this->stok_saat_ini} {$this->satuan}.");
        }
        $this->decrement('stok_saat_ini', $jumlah);
    }

    public function pembelianDetail()
    {
        return $this->hasMany(PembelianDetail::class, 'bahan_baku_id');
    }

    public function pemakaianDetail()
    {
        return $this->hasMany(PemakaianDetail::class, 'bahan_baku_id');
    }

    public function koreksiStok()
    {
        return $this->hasMany(KoreksiStok::class, 'bahan_baku_id');
    }

    public function eoqSetting()
    {
        return $this->hasOne(EoqSetting::class, 'bahan_baku_id');
    }

    public function permintaanDetail()
    {
        return $this->hasMany(PermintaanDetail::class, 'bahan_baku_id');
    }
}