<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom nib di suppliers jika belum ada
        if (Schema::hasTable('suppliers') && ! Schema::hasColumn('suppliers', 'nib')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->string('nib', 50)->nullable()->after('npwp');
            });
        }

        // 2. Tambah supplier_id di lahans jika belum ada
        if (Schema::hasTable('lahans') && ! Schema::hasColumn('lahans', 'supplier_id')) {
            Schema::table('lahans', function (Blueprint $table) {
                $table->foreignId('supplier_id')->nullable()->after('id')->constrained('suppliers')->cascadeOnDelete();
            });
        }

        // 3. Tambah lahan_id di documents jika belum ada & ubah plot_id jadi nullable
        if (Schema::hasTable('documents')) {
            Schema::table('documents', function (Blueprint $table) {
                if (! Schema::hasColumn('documents', 'lahan_id')) {
                    $table->foreignId('lahan_id')->nullable()->after('plot_id')->constrained('lahans')->cascadeOnDelete();
                }
                if (Schema::hasColumn('documents', 'plot_id')) {
                    $table->unsignedBigInteger('plot_id')->nullable()->change();
                }
            });
        }

        // 4. Data migration best-effort: plots -> lahans dan set documents.lahan_id
        if (Schema::hasTable('plots') && Schema::hasTable('lahans') && Schema::hasTable('documents')) {
            $plots = DB::table('plots')->get();
            foreach ($plots as $plot) {
                $supplier = DB::table('suppliers')->where('id', $plot->supplier_id)->first();
                $namaSupplier = $supplier?->name ?? ('Pemasok #' . $plot->supplier_id);
                $statusLahan = match ($plot->ownership_status ?? '') {
                    'rented' => 'Sewa',
                    default => 'Lainnya',
                };

                $existingLahan = DB::table('lahans')
                    ->where('supplier_id', $plot->supplier_id)
                    ->where('nama_kebun', $plot->name)
                    ->first();

                $lahanId = $existingLahan?->id;
                if (! $lahanId) {
                    $lahanId = DB::table('lahans')->insertGetId([
                        'supplier_id' => $plot->supplier_id,
                        'nama_kebun' => $plot->name,
                        'nama_pemasok' => $namaSupplier,
                        'lokasi' => $plot->regency ?: '-',
                        'luas_ha' => $plot->area_ha ?: 0,
                        'status_lahan' => $statusLahan,
                        'keterangan' => 'Dimigrasikan dari plot',
                        'created_at' => $plot->created_at ?? now(),
                        'updated_at' => $plot->updated_at ?? now(),
                    ]);
                }

                DB::table('documents')
                    ->where('plot_id', $plot->id)
                    ->whereNull('lahan_id')
                    ->update(['lahan_id' => $lahanId]);
            }
        }

        // 5. Tautkan orphan lahans yang nama_pemasok-nya cocok dengan nama supplier
        if (Schema::hasTable('suppliers') && Schema::hasTable('lahans')) {
            $suppliers = DB::table('suppliers')->get()->keyBy('name');
            $orphanLahans = DB::table('lahans')->whereNull('supplier_id')->get();
            foreach ($orphanLahans as $orphan) {
                if (isset($suppliers[$orphan->nama_pemasok])) {
                    DB::table('lahans')->where('id', $orphan->id)->update([
                        'supplier_id' => $suppliers[$orphan->nama_pemasok]->id,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('documents') && Schema::hasColumn('documents', 'lahan_id')) {
            Schema::table('documents', function (Blueprint $table) {
                $table->dropConstrainedForeignId('lahan_id');
            });
        }

        if (Schema::hasTable('lahans') && Schema::hasColumn('lahans', 'supplier_id')) {
            Schema::table('lahans', function (Blueprint $table) {
                $table->dropConstrainedForeignId('supplier_id');
            });
        }

        if (Schema::hasTable('suppliers') && Schema::hasColumn('suppliers', 'nib')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->dropColumn('nib');
            });
        }
    }
};
