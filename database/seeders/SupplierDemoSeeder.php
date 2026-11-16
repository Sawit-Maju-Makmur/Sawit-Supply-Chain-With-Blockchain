<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Plot;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

/** Data contoh untuk demo/uji: php artisan db:seed --class=SupplierDemoSeeder */
class SupplierDemoSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = collect([
            ['Koperasi Tani Makmur', 'mandiri', 'Kampar'],
            ['PT Sawit Lestari', 'perusahaan', 'Siak'],
            ['Pak Darman', 'plasma', 'Rokan Hulu'],
        ])->map(fn ($r) => Supplier::factory()->create(['name' => $r[0], 'type' => $r[1], 'regency' => $r[2]]));

        $suppliers->push(Supplier::factory()->inactive()->create(['name' => 'CV Kebun Harapan', 'type' => 'perusahaan', 'regency' => 'Pelalawan']));
        $suppliers = $suppliers->concat(Supplier::factory()->count(8)->create());

        foreach ($suppliers as $s) {
            foreach (range(1, random_int(1, 4)) as $i) {
                $plot = Plot::create([
                    'supplier_id' => $s->id,
                    'name' => 'Blok '.chr(64 + $i),
                    'regency' => $s->regency,
                    'area_ha' => random_int(15, 90) / 10,
                    'ownership_status' => collect(array_keys(Plot::OWNERSHIP))->random(),
                    'planting_year' => random_int(2010, 2021),
                ]);

                Document::create([
                    'plot_id' => $plot->id,
                    'type' => collect(['SHM', 'STDB', 'HGU', 'ISPO'])->random(),
                    'status' => collect(array_keys(Document::STATUSES))->random(),
                    'expires_at' => now()->addMonths(random_int(1, 48)),
                ]);
            }
        }
    }
}

