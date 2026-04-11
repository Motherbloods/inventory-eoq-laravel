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
        Schema::create('eoq_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_baku_id')
                ->unique()
                ->constrained('bahan_bakus')
                ->cascadeOnDelete();
            $table->decimal('permintaan_tahunan', 10, 2)
                ->comment('Estimasi kebutuhan per tahun (D)');
            $table->decimal('biaya_pemesanan', 12, 2)
                ->comment('Biaya per sekali pesan (S)');
            $table->decimal('biaya_penyimpanan', 12, 2)
                ->comment('Biaya simpan per satuan per tahun (H)');
            $table->integer('lead_time_hari')->default(1)
                ->comment('Waktu tunggu kedatangan bahan (hari)');
            $table->decimal('eoq_result', 10, 2)->nullable()
                ->comment('Hasil Q* = sqrt(2DS/H)');
            $table->decimal('reorder_point', 10, 2)->nullable()
                ->comment('ROP = (D/365) * lead_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eoq_settings');
    }
};
