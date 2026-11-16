<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('self_assessments')) {
            Schema::create('self_assessments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('nama_petani', 100);
                $table->string('nama_kebun', 100);
                $table->string('lokasi', 255);
                $table->decimal('luas_lahan', 10, 2);
                $table->unsignedSmallInteger('tahun_tanam')->nullable();
                $table->json('answers');
                $table->unsignedInteger('total_score');
                $table->unsignedInteger('max_score');
                $table->decimal('percentage', 5, 2);
                $table->string('level', 50);
                $table->text('catatan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('self_assessments');
    }
};
