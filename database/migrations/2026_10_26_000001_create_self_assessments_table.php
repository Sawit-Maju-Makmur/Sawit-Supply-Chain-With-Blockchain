<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('self_assessments', function (Blueprint $table) {
            $table->id();

            // Identitas petani & kebun
            $table->string('nama_petani');
            $table->string('nama_kebun');
            $table->string('lokasi');                       // desa/kecamatan/kabupaten
            $table->decimal('luas_lahan', 8, 2);            // hektare
            $table->unsignedSmallInteger('tahun_tanam')->nullable();

            // Jawaban kuesioner: {"legalitas_lahan":"ya","tanpa_bakar":"sebagian", ...}
            $table->json('answers');

            // Hasil penilaian
            $table->unsignedSmallInteger('total_score');
            $table->unsignedSmallInteger('max_score');
            $table->decimal('percentage', 5, 2);
            $table->string('level', 20);                    // Tinggi / Sedang / Rendah

            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('self_assessments');
    }
};
