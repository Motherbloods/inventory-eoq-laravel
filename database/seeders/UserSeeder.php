<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Dwi Riyanti',
                'email' => 'pemilik@tokorotiandika.com',
                'password' => Hash::make('pemilik123'),
                'role' => 'pemilik',
                'is_active' => true,
            ],
            [
                'name' => 'Admin Gudang',
                'email' => 'admin@tokorotiandika.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
            ],
            [
                'name' => 'Budi Produksi',
                'email' => 'produksi@tokorotiandika.com',
                'password' => Hash::make('produksi123'),
                'role' => 'produksi',
                'is_active' => true,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}