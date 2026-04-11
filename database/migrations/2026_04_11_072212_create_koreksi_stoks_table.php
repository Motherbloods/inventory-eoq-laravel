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
        Schema::create('koreksi_stoks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi', 30)->unique();
            $table->date('tanggal_koreksi');
            $table->foreignId('bahan_baku_id')
                ->constrained('bahan_bakus')
                ->restrictOnDelete();
            $table->decimal('jumlah_sebelum', 10, 2);
            $table->decimal('jumlah_sesudah', 10, 2);
            $table->decimal('selisih', 10, 2)
                ->comment('positif = penambahan, negatif = pengurangan');
            $table->text('alasan');
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('koreksi_stoks');
    }
};
