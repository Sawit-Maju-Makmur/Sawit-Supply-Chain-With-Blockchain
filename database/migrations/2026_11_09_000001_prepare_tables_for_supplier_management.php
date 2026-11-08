<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration PBI 9 (Kelola Data Pemasok) serta penyesuaian tabel pendukung.
 * Bersifat idempotent: hanya membuat tabel atau kolom yang belum ada.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $t) {
                $t->string('role', 30)->nullable()->after('email');
            });
        }

        if (! Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $t) {
                $t->id();
                $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $t->string('type', 20)->default('mandiri');       // mandiri | plasma | perusahaan
                $t->string('name');
                $t->string('nik', 16)->nullable();
                $t->string('npwp', 25)->nullable();
                $t->text('address')->nullable();
                $t->string('regency')->nullable();                // kabupaten
                $t->string('phone', 30)->nullable();
                $t->string('email')->nullable();
                $t->string('status', 20)->default('draft')->index(); // draft | active | inactive
                $t->text('deactivation_reason')->nullable();
                $t->timestamp('deactivated_at')->nullable();
                $t->timestamps();
                $t->softDeletes();
            });
        } else {
            Schema::table('suppliers', function (Blueprint $t) {
                if (! Schema::hasColumn('suppliers', 'regency')) {
                    $t->string('regency')->nullable();
                }
                if (! Schema::hasColumn('suppliers', 'status')) {
                    $t->string('status', 20)->default('draft')->index();
                }
                if (! Schema::hasColumn('suppliers', 'deactivation_reason')) {
                    $t->text('deactivation_reason')->nullable();
                }
                if (! Schema::hasColumn('suppliers', 'deactivated_at')) {
                    $t->timestamp('deactivated_at')->nullable();
                }
            });
        }

        if (! Schema::hasTable('plots')) {
            Schema::create('plots', function (Blueprint $t) {
                $t->id();
                $t->foreignId('supplier_id')->constrained()->cascadeOnDelete();
                $t->string('name');
                $t->string('regency')->nullable();
                $t->decimal('area_ha', 8, 2)->default(0);
                $t->string('ownership_status', 20)->default('owned'); // owned | rented | partnership
                $t->unsignedSmallInteger('planting_year')->nullable();
                $t->timestamps();
            });
        }

        if (! Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $t) {
                $t->id();
                $t->foreignId('plot_id')->constrained()->cascadeOnDelete();
                $t->string('type', 40)->default('SHM');
                $t->string('status', 20)->default('pending');   // pending | approved | rejected
                $t->date('expires_at')->nullable();
                $t->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Tetap aman: tabel ini bisa dibagikan dengan PBI lain, jangan dihapus sembarangan.
    }
};

