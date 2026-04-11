<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermintaanBahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_permintaan',
        'tanggal_permintaan',
        'user_id',
        'status',
        'catatan',
        'diproses_oleh',
    ];

    protected function casts(): array
    {
        return ['tanggal_permintaan' => 'date'];
    }

    public static function generateNomor(): string
    {
        $prefix = 'PMB-' . now()->format('Ymd');
        $last = self::where('nomor_permintaan', 'like', $prefix . '%')
            ->orderByDesc('nomor_permintaan')
            ->value('nomor_permintaan');
        $urutan = $last ? (int) substr($last, -3) + 1 : 1;
        return $prefix . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }
    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'disetujui' => 'badge bg-success',
            'ditolak' => 'badge bg-danger',
            default => 'badge bg-warning text-dark',
        };
    }

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pemroses()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function detail()
    {
        return $this->hasMany(PermintaanDetail::class, 'permintaan_id');
    }
}