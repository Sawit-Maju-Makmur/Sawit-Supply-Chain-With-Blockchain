<?php

namespace Database\Seeders;

use App\Models\AssessmentVersion;
use Illuminate\Database\Seeder;

class AssessmentCriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criteria = [
            'Sustainability' => [
                [
                    'name' => 'Bebas deforestasi',
                    'question_text' => 'Apakah pemasok bebas dari deforestasi?',
                    'weight_percentage' => 40,
                ],
                [
                    'name' => 'Pengelolaan limbah',
                    'question_text' => 'Apakah pemasok mengelola limbah sesuai praktik baik?',
                    'weight_percentage' => 30,
                ],
                [
                    'name' => 'Pelatihan praktik baik',
                    'question_text' => 'Apakah pemasok memberikan pelatihan praktik baik?',
                    'weight_percentage' => 30,
                ],
            ],
            'Traceability' => [
                [
                    'name' => 'Pencatatan panen per blok',
                    'question_text' => 'Apakah panen dicatat berdasarkan blok?',
                    'weight_percentage' => 50,
                ],
                [
                    'name' => 'Pelaporan TBS ke mill',
                    'question_text' => 'Apakah TBS dilaporkan ke mill?',
                    'weight_percentage' => 50,
                ],
            ],
        ];

        foreach ([1 => false, 2 => true] as $versionNumber => $isActive) {
            $version = AssessmentVersion::updateOrCreate(
                ['version_number' => $versionNumber],
                [
                    'is_active' => $isActive,
                    'notes' => "Konfigurasi awal versi {$versionNumber}.",
                ],
            );

            $version->criteria()->delete();

            foreach ($criteria as $category => $items) {
                $version->criteria()->createMany(
                    collect($items)
                        ->map(fn (array $item): array => [...$item, 'category' => $category])
                        ->all(),
                );
            }
        }
    }
}
