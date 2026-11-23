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
        if (Schema::hasColumn('lahans', 'nama_pemasok')) {
            Schema::table('lahans', function (Blueprint $table) {
                $table->dropColumn('nama_pemasok');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('lahans', 'nama_pemasok')) {
            Schema::table('lahans', function (Blueprint $table) {
                $table->string('nama_pemasok')->nullable()->after('supplier_id');
            });
        }
    }
};
