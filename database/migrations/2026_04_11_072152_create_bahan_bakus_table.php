<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bahan', 20)->unique();
            $table->string('nama_bahan', 100);
            $table->string('kategori', 50);
            $table->string('satuan', 20);
            $table->decimal('harga_satuan', 12, 2)->default(0);
            $table->decimal('stok_minimum', 10, 2)->default(0);
            $table->decimal('stok_saat_ini', 10, 2)->default(0);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bahan_bakus');
    }
};
