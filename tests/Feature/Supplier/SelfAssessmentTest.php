<?php

namespace Tests\Feature\Supplier;

use App\Models\SelfAssessment;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SelfAssessmentTest extends TestCase
{
    use RefreshDatabase;

    private function validAssessmentData(array $overrides = []): array
    {
        $answers = [
            'legalitas_lahan'    => 'ya',
            'koordinat_kebun'    => 'ya',
            'catatan_panen'      => 'ya',
            'nota_pengiriman'    => 'ya',
            'asal_bibit'         => 'ya',
            'tanpa_bakar'        => 'ya',
            'tanpa_deforestasi'  => 'ya',
            'pestisida_aman'     => 'ya',
            'pupuk_berimbang'    => 'ya',
            'sempadan_air'       => 'ya',
            'pengelolaan_limbah' => 'ya',
            'apd_pekerja'        => 'ya',
            'tanpa_pekerja_anak' => 'ya',
            'anggota_kelompok'   => 'ya',
            'sertifikasi'        => 'ya',
        ];

        return array_merge([
            'nama_petani' => 'Pak Tani Sawit',
            'nama_kebun'  => 'Kebun Berkah',
            'lokasi'      => 'Kab. Kampar, Riau',
            'luas_lahan'  => 4.5,
            'tahun_tanam' => 2018,
            'catatan'     => 'Kebun percontohan',
            'answers'     => $answers,
        ], $overrides);
    }

    public function test_pemasok_can_view_index_and_create_form(): void
    {
        $user = User::factory()->create(['role' => 'pemasok']);
        $user->ensureSupplier();

        $this->actingAs($user)
            ->get(route('assessments.index'))
            ->assertOk()
            ->assertSee('Self-Assessment');

        $this->actingAs($user)
            ->get(route('assessments.create'))
            ->assertOk()
            ->assertSee('Formulir Mandiri');
    }

    public function test_pemasok_can_submit_and_view_assessment(): void
    {
        $user = User::factory()->create(['role' => 'pemasok']);
        $supplier = $user->ensureSupplier();

        $data = $this->validAssessmentData();

        $response = $this->actingAs($user)
            ->post(route('assessments.store'), $data);

        $assessment = SelfAssessment::first();
        $this->assertNotNull($assessment);
        $this->assertEquals($supplier->id, $assessment->supplier_id);
        $this->assertEquals($user->id, $assessment->user_id);
        $this->assertEquals(100.0, (float) $assessment->percentage);
        $this->assertEquals('Tinggi', $assessment->level);

        $response->assertRedirect(route('assessments.show', $assessment))
            ->assertSessionHas('success');

        $this->actingAs($user)
            ->get(route('assessments.show', $assessment))
            ->assertOk()
            ->assertSee('Kebun Berkah')
            ->assertSee('Tinggi')
            ->assertSee('100%');
    }

    public function test_pemasok_cannot_view_or_delete_other_supplier_assessment(): void
    {
        $userA = User::factory()->create(['role' => 'pemasok']);
        $supplierA = $userA->ensureSupplier();

        $userB = User::factory()->create(['role' => 'pemasok']);
        $supplierB = $userB->ensureSupplier();

        $calc = SelfAssessment::calculate($this->validAssessmentData()['answers']);
        $assessmentB = SelfAssessment::create(array_merge(
            $this->validAssessmentData(),
            $calc,
            ['supplier_id' => $supplierB->id, 'user_id' => $userB->id]
        ));

        // User A accessing User B's assessment gets 404
        $this->actingAs($userA)
            ->get(route('assessments.show', $assessmentB))
            ->assertNotFound();

        $this->actingAs($userA)
            ->delete(route('assessments.destroy', $assessmentB))
            ->assertNotFound();
    }

    public function test_non_pemasok_cannot_access_assessment_routes(): void
    {
        $officer = User::factory()->create(['role' => 'sustainability_officer']);

        $this->actingAs($officer)
            ->get(route('assessments.index'))
            ->assertForbidden();

        $this->actingAs($officer)
            ->get(route('assessments.create'))
            ->assertForbidden();
    }

    public function test_pemasok_can_delete_their_own_assessment(): void
    {
        $user = User::factory()->create(['role' => 'pemasok']);
        $supplier = $user->ensureSupplier();

        $calc = SelfAssessment::calculate($this->validAssessmentData()['answers']);
        $assessment = SelfAssessment::create(array_merge(
            $this->validAssessmentData(),
            $calc,
            ['supplier_id' => $supplier->id, 'user_id' => $user->id]
        ));

        $this->actingAs($user)
            ->delete(route('assessments.destroy', $assessment))
            ->assertRedirect(route('assessments.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('self_assessments', ['id' => $assessment->id]);
    }
}
