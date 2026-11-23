<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SelfAssessment extends Model
{
    protected $fillable = [
        'supplier_id',
        'user_id',
        'nama_petani',
        'nama_kebun',
        'lokasi',
        'luas_lahan',
        'tahun_tanam',
        'answers',
        'total_score',
        'max_score',
        'percentage',
        'level',
        'catatan',
    ];

    protected $casts = [
        'answers'    => 'array',
        'luas_lahan' => 'decimal:2',
        'percentage' => 'decimal:2',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hitung skor, persentase, dan level kepatuhan dari jawaban.
     *
     * @param  array<string,string>  $answers  contoh: ['tanpa_bakar' => 'ya']
     * @return array{total_score:int,max_score:int,percentage:float,level:string}
     */
    
    public static function calculate(array $answers): array
    {
        $criteria = AssessmentCriterion::query()
            ->where('is_active', true)
            ->whereNotNull('question_key')
            ->get()
            ->groupBy('category');

        // Tabel kriteria belum terisi: pakai perhitungan lama.
        if ($criteria->isEmpty()) {
            return self::calculateLegacy($answers);
        }

        $options = config('assessment.options');
        $maxOption = max(array_column($options, 'score'));
        $categoryPercentages = [];

        foreach ($criteria as $items) {
            $weightSum = (float) $items->sum('weight');
            if ($weightSum <= 0) {
                continue;
            }

            $earned = 0.0;
            foreach ($items as $criterion) {
                $score = $options[$answers[$criterion->question_key] ?? 'tidak']['score'] ?? 0;
                $earned += (float) $criterion->weight * ($score / $maxOption);
            }

            $categoryPercentages[] = $earned / $weightSum * 100;
        }

        if ($categoryPercentages === []) {
            return self::calculateLegacy($answers);
        }

        // Skor akhir = rata-rata persentase tiap kategori (50:50).
        $percentage = round(array_sum($categoryPercentages) / count($categoryPercentages), 2);

        return [
            'total_score' => (int) round($percentage),
            'max_score'   => 100,
            'percentage'  => $percentage,
            'level'       => self::levelFor($percentage)['label'],
        ];
    }

    
    public static function calculateLegacy(array $answers): array
    {
        $options = config('assessment.options');
        $total = 0;
        $count = 0;

        foreach (config('assessment.categories') as $category) {
            foreach (array_keys($category['questions']) as $key) {
                $count++;
                $total += $options[$answers[$key] ?? 'tidak']['score'] ?? 0;
            }
        }

        $max = $count * max(array_column($options, 'score'));
        $percentage = $max > 0 ? round($total / $max * 100, 2) : 0;

        return [
            'total_score' => $total,
            'max_score'   => $max,
            'percentage'  => $percentage,
            'level'       => self::levelFor($percentage)['label'],
        ];
    }

    /** Ambil konfigurasi level (label, class, note) berdasarkan persentase. */
    public static function levelFor(float $percentage): array
    {
        $levels = config('assessment.levels');

        foreach ($levels as $level) {
            if ($percentage >= $level['min']) {
                return $level;
            }
        }

        return end($levels);
    }

    public function getLevelInfoAttribute(): array
    {
        return self::levelFor((float) $this->percentage);
    }
}
