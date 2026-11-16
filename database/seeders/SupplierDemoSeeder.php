<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Lahan;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

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

        foreach ($suppliers as $s) {
            foreach (range(1, random_int(1, 4)) as $i) {
                $statusList = array_keys(Lahan::STATUS);
                $lahan = Lahan::create([
                    'supplier_id' => $s->id,
                    'nama_pemasok' => $s->name,
                    'nama_kebun' => 'Blok '.chr(64 + $i),
                    'lokasi' => $s->regency ?: 'Riau',
                    'luas_ha' => random_int(15, 90) / 10,
                    'status_lahan' => $statusList[array_rand($statusList)],
                    'no_dokumen' => 'DOK-'.random_int(1000, 9999),
                    'keterangan' => 'Kebun sawit produktif',
                ]);

                Document::create([
                    'lahan_id' => $lahan->id,
                    'type' => collect(['SHM', 'STDB', 'HGU', 'ISPO'])->random(),
                    'status' => collect(array_keys(Document::STATUSES))->random(),
                    'expires_at' => now()->addMonths(random_int(1, 48)),
                ]);
            }
        }
    }
}

