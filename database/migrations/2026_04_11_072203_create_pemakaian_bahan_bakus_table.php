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
        Schema::create('pemakaian_bahan_bakus', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_transaksi', 30)->unique();
            $table->date('tanggal_pemakaian');
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemakaian_bahan_bakus');
    }
};
