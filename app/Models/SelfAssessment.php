<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SelfAssessment extends Model
{
    protected $fillable = [
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

    /**
     * Hitung skor, persentase, dan level kepatuhan dari jawaban.
     *
     * @param  array<string,string>  $answers  contoh: ['tanpa_bakar' => 'ya']
     * @return array{total_score:int,max_score:int,percentage:float,level:string}
     */
    public static function calculate(array $answers): array
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
