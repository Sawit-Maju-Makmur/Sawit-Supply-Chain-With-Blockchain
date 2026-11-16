<?php

namespace Tests\Feature;

use App\Models\SelfAssessment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SelfAssessmentTest extends TestCase
{
    use RefreshDatabase;

    private function validAssessmentData(array $overrides = []): array
    {
        $answers = [];
        foreach (config('assessment.categories') as $category) {
            foreach (array_keys($category['questions']) as $key) {
                $answers[$key] = 'ya';
            }
        }

        return array_merge([
            'nama_petani' => 'Pak Budi',
            'nama_kebun'  => 'Kebun Sawit Mandiri',
            'lokasi'      => 'Kec. Tapung, Kab. Kampar',
            'luas_lahan'  => 5.5,
            'tahun_tanam' => 2018,
            'answers'     => $answers,
            'catatan'     => 'Kebun terkelola dengan baik.',
        ], $overrides);
    }

    public function test_assessments_index_can_be_rendered(): void
    {
        $response = $this->get(route('assessments.index'));
        $response->assertOk();
        $response->assertSee('Riwayat Self-Assessment');
    }

    public function test_assessment_create_page_can_be_rendered(): void
    {
        $response = $this->get(route('assessments.create'));
        $response->assertOk();
        $response->assertSee('Formulir Mandiri (Self-Assessment)');
    }

    public function test_assessment_can_be_submitted_and_evaluated(): void
    {
        $data = $this->validAssessmentData();

        $response = $this->post(route('assessments.store'), $data);

        $assessment = SelfAssessment::first();
        $this->assertNotNull($assessment);
        $this->assertSame('Pak Budi', $assessment->nama_petani);
        $this->assertSame(100.0, (float) $assessment->percentage);
        $this->assertSame('Tinggi', $assessment->level);

        $response->assertRedirect(route('assessments.show', $assessment));
    }

    public function test_assessment_detail_can_be_viewed(): void
    {
        $data = $this->validAssessmentData();
        $result = SelfAssessment::calculate($data['answers']);
        $assessment = SelfAssessment::create($data + $result);

        $response = $this->get(route('assessments.show', $assessment));
        $response->assertOk();
        $response->assertSee('Pak Budi');
        $response->assertSee('Kebun Sawit Mandiri');
        $response->assertSee('Hasil Evaluasi Kepatuhan');
    }

    public function test_assessment_can_be_deleted(): void
    {
        $data = $this->validAssessmentData();
        $result = SelfAssessment::calculate($data['answers']);
        $assessment = SelfAssessment::create($data + $result);

        $response = $this->delete(route('assessments.destroy', $assessment));

        $response->assertRedirect(route('assessments.index'));
        $this->assertDatabaseMissing('self_assessments', ['id' => $assessment->id]);
    }

    public function test_validation_fails_when_required_fields_are_missing(): void
    {
        $response = $this->post(route('assessments.store'), []);

        $response->assertSessionHasErrors(['nama_petani', 'nama_kebun', 'lokasi', 'luas_lahan']);
    }
}
