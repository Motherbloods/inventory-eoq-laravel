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
        Schema::create('permintaan_bahans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_permintaan', 30)->unique();
            $table->date('tanggal_permintaan');
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->comment('User produksi yang mengajukan');
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable();
            $table->foreignId('diproses_oleh')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Admin yang approve/tolak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_bahans');
    }
};
