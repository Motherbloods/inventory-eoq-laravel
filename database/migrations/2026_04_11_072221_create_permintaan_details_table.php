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
        Schema::create('permintaan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_id')
                ->constrained('permintaan_bahans')
                ->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')
                ->constrained('bahan_bakus')
                ->restrictOnDelete();
            $table->decimal('jumlah_diminta', 10, 2);
            $table->decimal('jumlah_disetujui', 10, 2)->nullable()
                ->comment('Diisi admin saat approve, bisa berbeda dari yang diminta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_details');
    }
};
