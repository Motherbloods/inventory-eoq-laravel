<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BahanBaku;

class BahanBakuSeeder extends Seeder
{
    public function run(): void
    {
        $bahanBakus = [
            [
                'kode_bahan' => 'BB-001',
                'nama_bahan' => 'Tepung Terigu Protein Tinggi',
                'kategori' => 'Tepung',
                'satuan' => 'kg',
                'harga_satuan' => 13500,
                'stok_minimum' => 50,
                'stok_saat_ini' => 120,
                'deskripsi' => 'Tepung protein tinggi untuk roti tawar dan roti manis',
            ],
            [
                'kode_bahan' => 'BB-002',
                'nama_bahan' => 'Tepung Terigu Protein Sedang',
                'kategori' => 'Tepung',
                'satuan' => 'kg',
                'harga_satuan' => 12000,
                'stok_minimum' => 30,
                'stok_saat_ini' => 75,
                'deskripsi' => 'Tepung serbaguna untuk berbagai jenis roti',
            ],
            [
                'kode_bahan' => 'BB-003',
                'nama_bahan' => 'Tepung Maizena',
                'kategori' => 'Tepung',
                'satuan' => 'kg',
                'harga_satuan' => 18000,
                'stok_minimum' => 10,
                'stok_saat_ini' => 25,
                'deskripsi' => 'Digunakan sebagai bahan campuran untuk tekstur lebih lembut',
            ],

            [
                'kode_bahan' => 'BB-004',
                'nama_bahan' => 'Gula Pasir',
                'kategori' => 'Gula & Pemanis',
                'satuan' => 'kg',
                'harga_satuan' => 15000,
                'stok_minimum' => 25,
                'stok_saat_ini' => 60,
                'deskripsi' => 'Gula pasir putih untuk adonan roti manis',
            ],
            [
                'kode_bahan' => 'BB-005',
                'nama_bahan' => 'Gula Halus (Icing Sugar)',
                'kategori' => 'Gula & Pemanis',
                'satuan' => 'kg',
                'harga_satuan' => 22000,
                'stok_minimum' => 5,
                'stok_saat_ini' => 12,
                'deskripsi' => 'Digunakan untuk taburan dan topping roti',
            ],

            [
                'kode_bahan' => 'BB-006',
                'nama_bahan' => 'Margarin',
                'kategori' => 'Lemak & Minyak',
                'satuan' => 'kg',
                'harga_satuan' => 24000,
                'stok_minimum' => 20,
                'stok_saat_ini' => 45,
                'deskripsi' => 'Margarin untuk adonan dan olesan',
            ],
            [
                'kode_bahan' => 'BB-007',
                'nama_bahan' => 'Minyak Goreng',
                'kategori' => 'Lemak & Minyak',
                'satuan' => 'liter',
                'harga_satuan' => 18000,
                'stok_minimum' => 15,
                'stok_saat_ini' => 30,
                'deskripsi' => 'Minyak goreng untuk jenis roti goreng dan loyang',
            ],
            [
                'kode_bahan' => 'BB-008',
                'nama_bahan' => 'Butter (Mentega)',
                'kategori' => 'Lemak & Minyak',
                'satuan' => 'kg',
                'harga_satuan' => 65000,
                'stok_minimum' => 5,
                'stok_saat_ini' => 8,
                'deskripsi' => 'Mentega tawar untuk roti premium',
            ],

            [
                'kode_bahan' => 'BB-009',
                'nama_bahan' => 'Telur Ayam',
                'kategori' => 'Telur & Susu',
                'satuan' => 'kg',
                'harga_satuan' => 28000,
                'stok_minimum' => 10,
                'stok_saat_ini' => 20,
                'deskripsi' => 'Telur ayam segar, dihitung per kg',
            ],

            [
                'kode_bahan' => 'BB-010',
                'nama_bahan' => 'Susu Bubuk Full Cream',
                'kategori' => 'Telur & Susu',
                'satuan' => 'kg',
                'harga_satuan' => 75000,
                'stok_minimum' => 5,
                'stok_saat_ini' => 10,
                'deskripsi' => 'Susu bubuk untuk adonan roti manis dan tawar',
            ],
            [
                'kode_bahan' => 'BB-011',
                'nama_bahan' => 'Susu Cair UHT',
                'kategori' => 'Telur & Susu',
                'satuan' => 'liter',
                'harga_satuan' => 16000,
                'stok_minimum' => 10,
                'stok_saat_ini' => 18,
                'deskripsi' => 'Susu cair UHT full cream untuk adonan',
            ],

            [
                'kode_bahan' => 'BB-012',
                'nama_bahan' => 'Ragi Instan (Instant Yeast)',
                'kategori' => 'Ragi & Pengembang',
                'satuan' => 'kg',
                'harga_satuan' => 120000,
                'stok_minimum' => 2,
                'stok_saat_ini' => 5,
                'deskripsi' => 'Ragi instan untuk fermentasi adonan roti',
            ],
            [
                'kode_bahan' => 'BB-013',
                'nama_bahan' => 'Baking Powder',
                'kategori' => 'Ragi & Pengembang',
                'satuan' => 'kg',
                'harga_satuan' => 45000,
                'stok_minimum' => 1,
                'stok_saat_ini' => 3,
                'deskripsi' => 'Baking powder sebagai bahan pengembang tambahan',
            ],

            [
                'kode_bahan' => 'BB-014',
                'nama_bahan' => 'Garam Halus',
                'kategori' => 'Bahan Tambahan',
                'satuan' => 'kg',
                'harga_satuan' => 5000,
                'stok_minimum' => 5,
                'stok_saat_ini' => 15,
                'deskripsi' => 'Garam untuk penyeimbang rasa adonan',
            ],
            [
                'kode_bahan' => 'BB-015',
                'nama_bahan' => 'Bread Improver',
                'kategori' => 'Bahan Tambahan',
                'satuan' => 'kg',
                'harga_satuan' => 85000,
                'stok_minimum' => 1,
                'stok_saat_ini' => 2,
                'deskripsi' => 'Improver untuk meningkatkan kualitas tekstur roti',
            ],
            [
                'kode_bahan' => 'BB-016',
                'nama_bahan' => 'Coklat Bubuk',
                'kategori' => 'Bahan Tambahan',
                'satuan' => 'kg',
                'harga_satuan' => 90000,
                'stok_minimum' => 3,
                'stok_saat_ini' => 4,
                'deskripsi' => 'Coklat bubuk untuk roti rasa coklat',
            ],
            [
                'kode_bahan' => 'BB-017',
                'nama_bahan' => 'Selai Coklat (Filling)',
                'kategori' => 'Isian & Topping',
                'satuan' => 'kg',
                'harga_satuan' => 55000,
                'stok_minimum' => 5,
                'stok_saat_ini' => 7,
                'deskripsi' => 'Selai coklat untuk isian roti',
            ],
            [
                'kode_bahan' => 'BB-018',
                'nama_bahan' => 'Selai Kacang',
                'kategori' => 'Isian & Topping',
                'satuan' => 'kg',
                'harga_satuan' => 48000,
                'stok_minimum' => 3,
                'stok_saat_ini' => 6,
                'deskripsi' => 'Selai kacang untuk varian roti isian',
            ],
            [
                'kode_bahan' => 'BB-019',
                'nama_bahan' => 'Wijen',
                'kategori' => 'Isian & Topping',
                'satuan' => 'kg',
                'harga_satuan' => 35000,
                'stok_minimum' => 2,
                'stok_saat_ini' => 3,
                'deskripsi' => 'Biji wijen untuk taburan roti',
            ],

            [
                'kode_bahan' => 'BB-020',
                'nama_bahan' => 'Plastik Kemasan Roti (pcs)',
                'kategori' => 'Kemasan',
                'satuan' => 'pcs',
                'harga_satuan' => 350,
                'stok_minimum' => 500,
                'stok_saat_ini' => 1200,
                'deskripsi' => 'Plastik kemasan ukuran standar per pcs',
            ],
        ];

        foreach ($bahanBakus as $data) {
            BahanBaku::updateOrCreate(
                ['kode_bahan' => $data['kode_bahan']],
                $data
            );
        }
    }
}