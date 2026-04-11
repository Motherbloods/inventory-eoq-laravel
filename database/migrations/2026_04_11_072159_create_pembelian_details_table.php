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
        Schema::create('pembelian_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')
                ->constrained('pembelian_bahan_bakus')
                ->cascadeOnDelete();
            $table->foreignId('bahan_baku_id')
                ->constrained('bahan_bakus')
                ->restrictOnDelete();
            $table->decimal('jumlah', 10, 2);
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 14, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_details');
    }
};
