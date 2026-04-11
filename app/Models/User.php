<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isPemilik(): bool
    {
        return $this->role === 'pemilik';
    }
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isProduksi(): bool
    {
        return $this->role === 'produksi';
    }

    public function pembelian()
    {
        return $this->hasMany(PembelianBahanBaku::class, 'user_id');
    }

    public function pemakaian()
    {
        return $this->hasMany(PemakaianBahanBaku::class, 'user_id');
    }

    public function koreksiStok()
    {
        return $this->hasMany(KoreksiStok::class, 'user_id');
    }

    public function permintaanDiajukan()
    {
        return $this->hasMany(PermintaanBahan::class, 'user_id');
    }

    public function permintaanDiproses()
    {
        return $this->hasMany(PermintaanBahan::class, 'diproses_oleh');
    }
}