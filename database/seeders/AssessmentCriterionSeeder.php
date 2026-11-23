<?php

namespace Database\Seeders;

use App\Models\AssessmentCriterion;
use Illuminate\Database\Seeder;

class AssessmentCriterionSeeder extends Seeder
{
    public function run(): void
    {
        // Kategori di config dipetakan ke dua kategori milik officer.
        $categoryMap = [
            'traceability' => 'traceability',
            'lingkungan'   => 'sustainability',
            'sosial'       => 'sustainability',
        ];

        $grouped = [];
        foreach (config('assessment.categories') as $group => $cfg) {
            foreach ($cfg['questions'] as $key => $text) {
                $grouped[$categoryMap[$group]][] = [$key, $text];
            }
        }

        foreach ($grouped as $category => $items) {
            $weight = round(100 / count($items), 2);

            foreach ($items as [$key, $text]) {
                // firstOrCreate: menjalankan ulang seeder tidak menimpa perubahan officer.
                AssessmentCriterion::firstOrCreate(
                    ['question_key' => $key],
                    [
                        'category'    => $category,
                        'name'        => $text,
                        'description' => $text,
                        'weight'      => $weight,
                        'is_active'   => true,
                    ]
                );
            }
        }
    }
}