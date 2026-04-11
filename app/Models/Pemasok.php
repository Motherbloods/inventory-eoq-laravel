<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemasok extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pemasok',
        'kontak_person',
        'telepon',
        'alamat',
        'email',
        'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function pembelian()
    {
        return $this->hasMany(PembelianBahanBaku::class, 'pemasok_id');
    }
}