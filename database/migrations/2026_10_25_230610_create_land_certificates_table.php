<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('land_certificates', function (Blueprint $table) {
        $table->id();
        $table->string('farmer_id');
        $table->string('document_name');
        $table->string('file_path');
        $table->enum('certification_type', ['ISPO', 'RSPO', 'SKGR', 'Lainnya']);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('land_certificates');
    }
};
