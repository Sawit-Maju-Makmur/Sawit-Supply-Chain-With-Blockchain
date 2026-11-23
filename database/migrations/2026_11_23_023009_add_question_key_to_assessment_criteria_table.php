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
        Schema::table('assessment_criteria', function (Blueprint $table) {
            $table->string('question_key', 60)->nullable()->unique()->after('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_criteria', function (Blueprint $table) {
            $table->dropUnique(['question_key']);
            $table->dropColumn('question_key');
        });
    }
};
