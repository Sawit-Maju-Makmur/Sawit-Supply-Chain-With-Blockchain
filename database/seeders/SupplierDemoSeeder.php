<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Lahan;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/** Data contoh untuk demo/uji: php artisan db:seed --class=SupplierDemoSeeder */
class SupplierDemoSeeder extends Seeder
{
    public function run(): void
    {
        $pemasokUser = User::where('email', 'pemasok@sawit.test')->first();

        $suppliers = collect([
            [
                'name' => 'Koperasi Tani Makmur',
                'type' => 'mandiri',
                'regency' => 'Kampar',
                'user_id' => $pemasokUser?->id,
                'email' => 'pemasok@sawit.test',
            ],
            [
                'name' => 'PT Sawit Lestari',
                'type' => 'perusahaan',
                'regency' => 'Siak',
            ],
            [
                'name' => 'Pak Darman',
                'type' => 'plasma',
                'regency' => 'Rokan Hulu',
            ],
        ])->map(fn ($r) => Supplier::factory()->create($r));

        $suppliers->push(Supplier::factory()->inactive()->create(['name' => 'CV Kebun Harapan', 'type' => 'perusahaan', 'regency' => 'Pelalawan']));
        $suppliers = $suppliers->concat(Supplier::factory()->count(8)->create());

        $officer = User::where('email', 'officer@sawit.test')->first();

        foreach ($suppliers as $s) {
            // Pemasok aktif wajib punya semua dokumennya approved dan belum kedaluwarsa.
            $mustBeValid = $s->status === Supplier::STATUS_ACTIVE;

            foreach (range(1, random_int(1, 4)) as $i) {
                $statusList = array_keys(Lahan::STATUS);
                $lahan = Lahan::create([
                    'supplier_id' => $s->id,
                    'nama_kebun' => 'Blok '.chr(64 + $i),
                    'lokasi' => $s->regency ?: 'Riau',
                    'luas_ha' => random_int(15, 90) / 10,
                    'status_lahan' => $statusList[array_rand($statusList)],
                    'no_dokumen' => 'DOK-'.random_int(1000, 9999),
                    'keterangan' => 'Kebun sawit produktif',
                ]);

                $status = $mustBeValid
                    ? 'approved'
                    : collect(array_keys(Document::STATUSES))->random();

                // Berkas contoh sungguhan, supaya tombol unduh dan pengecekan file_path berfungsi.
                $path = "legal-documents/{$s->id}/demo-{$lahan->id}.pdf";
                Storage::disk('local')->put($path, "%PDF-1.4\n% dokumen contoh untuk demo\n");

                Document::create([
                    'lahan_id' => $lahan->id,
                    'type' => collect(['SHM', 'STDB', 'HGU', 'ISPO'])->random(),
                    'file_path' => $path,
                    'original_filename' => "dokumen-{$lahan->id}.pdf",
                    'status' => $status,
                    'expires_at' => now()->addMonths(random_int(6, 48)),
                    'reviewed_by' => $status === 'pending' ? null : $officer?->id,
                    'reviewed_at' => $status === 'pending' ? null : now(),
                    'review_notes' => $status === 'rejected' ? 'Dokumen contoh ditolak untuk keperluan demo.' : null,
                ]);
            }
        }
    }
}

