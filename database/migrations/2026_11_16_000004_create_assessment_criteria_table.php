<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('version_id')->constrained('assessment_versions')->cascadeOnDelete();
            $table->string('category');
            $table->string('name');
            $table->string('question_text');
            $table->decimal('weight_percentage', 5, 2);
            $table->timestamps();

            $table->index(['version_id', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_criteria');
    }
};
