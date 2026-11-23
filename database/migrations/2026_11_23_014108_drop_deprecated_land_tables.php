<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('land_certificates');
        Schema::dropIfExists('land_areas');
    }

    public function down(): void
    {
        Schema::create('land_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('farmer_id');
            $table->string('document_name');
            $table->string('file_path');
            $table->enum('certification_type', ['ISPO', 'RSPO', 'SKGR', 'Lainnya']);
            $table->timestamps();
        });

        Schema::create('land_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->json('polygon_coordinates');
            $table->timestamps();
        });
    }
};