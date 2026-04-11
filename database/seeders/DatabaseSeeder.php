<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PemasokSeeder::class,
            BahanBakuSeeder::class,
            EoqSettingSeeder::class,
        ]);
    }
}