<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harvests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('lahan_id')->constrained('lahans')->cascadeOnDelete();
            $table->string('kode_panen', 100)->unique()->comment('Kode unik panen, misal: HRV-2026-0001');
            $table->string('jenis_komoditas', 100)->default('Tandan Buah Segar (TBS)');
            $table->decimal('jumlah_kg', 10, 2)->comment('Berat hasil panen dalam kg');
            $table->date('tanggal_panen');
            $table->text('keterangan')->nullable();

            // Blockchain fields
            $table->string('tx_hash', 100)->nullable()->unique()->comment('Transaction hash di blockchain');
            $table->string('block_number', 50)->nullable()->comment('Nomor blok di blockchain');
            $table->string('blockchain_status', 20)->default('pending')
                ->comment('pending | confirmed | failed');
            $table->timestamp('blockchain_confirmed_at')->nullable();

            // QR Code
            $table->string('qr_token', 80)->nullable()->unique()->comment('Token unik untuk URL QR Code');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harvests');
    }
};
