<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('supplier_status_logs')) {
            Schema::create('supplier_status_logs', function (Blueprint $t) {
                $t->id();
                $t->foreignId('supplier_id')->constrained()->cascadeOnDelete();
                $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Management
                $t->string('from_status', 20);
                $t->string('to_status', 20);
                $t->text('reason')->nullable();
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_status_logs');
    }
};

