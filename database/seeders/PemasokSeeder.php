<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pemasok;

class PemasokSeeder extends Seeder
{
    public function run(): void
    {
        $pemasoks = [
            [
                'nama_pemasok' => 'UD. Sumber Terigu Makmur',
                'kontak_person' => 'Pak Harto',
                'telepon' => '08112345678',
                'alamat' => 'Jl. Raya Solo-Semarang No. 45, Karanganyar',
                'email' => 'sumberterigu@gmail.com',
                'is_active' => true,
            ],
            [
                'nama_pemasok' => 'CV. Gula Jawa Sejahtera',
                'kontak_person' => 'Bu Sari',
                'telepon' => '08223456789',
                'alamat' => 'Jl. Pasar Gedhe No. 12, Surakarta',
                'email' => null,
                'is_active' => true,
            ],
            [
                'nama_pemasok' => 'Toko Minyak & Lemak Indah',
                'kontak_person' => 'Pak Agus',
                'telepon' => '08534567890',
                'alamat' => 'Jl. Veteran No. 7, Colomadu, Karanganyar',
                'email' => null,
                'is_active' => true,
            ],
            [
                'nama_pemasok' => 'PT. Indofood CBP Sukses Makmur',
                'kontak_person' => 'Sales Representative',
                'telepon' => '02145678901',
                'alamat' => 'Jl. Gatot Subroto Kav. 38, Jakarta Selatan',
                'email' => 'sales.jateng@indofood.com',
                'is_active' => true,
            ],
            [
                'nama_pemasok' => 'UD. Telur Ayam Mas',
                'kontak_person' => 'Bu Endang',
                'telepon' => '08156789012',
                'alamat' => 'Desa Jaten, Karanganyar',
                'email' => null,
                'is_active' => true,
            ],
            [
                'nama_pemasok' => 'Koperasi Susu Segar Boyolali',
                'kontak_person' => 'Pak Eko',
                'telepon' => '08267890123',
                'alamat' => 'Jl. Pandanaran No. 3, Boyolali',
                'email' => 'kopsusegar@gmail.com',
                'is_active' => true,
            ],
        ];

        foreach ($pemasoks as $data) {
            Pemasok::updateOrCreate(
                [
                    'nama_pemasok' => $data['nama_pemasok']
                ],
                $data
            );
        }
    }
}