<?php

namespace Tests\Feature\Officer;

use App\Models\AssessmentCriterion;
use App\Models\Document;
use App\Models\Plot;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SustainabilityGovernanceTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    public function test_officer_can_save_criteria_when_active_weights_total_one_hundred_percent(): void
    {
        $officer = $this->userWithRole('sustainability_officer');

        $this->actingAs($officer)
            ->put(route('officer.criteria.update', 'sustainability'), [
                'category' => 'sustainability',
                'criteria' => [
                    ['name' => 'Tanpa deforestasi', 'description' => 'Indikator A', 'regulatory_reference' => 'Regulasi A', 'weight' => '60', 'active' => '1'],
                    ['name' => 'Perlindungan gambut', 'description' => 'Indikator B', 'regulatory_reference' => 'Regulasi B', 'weight' => '40', 'active' => '1'],
                ],
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseCount('assessment_criteria', 2);
        $this->assertDatabaseHas('assessment_criteria', [
            'category' => AssessmentCriterion::CATEGORY_SUSTAINABILITY,
            'name' => 'Tanpa deforestasi',
            'weight' => 60,
            'updated_by' => $officer->id,
        ]);
    }

    public function test_criteria_save_is_rejected_when_active_weights_do_not_total_one_hundred_percent(): void
    {
        $this->actingAs($this->userWithRole('sustainability_officer'))
            ->from(route('officer.criteria.index'))
            ->put(route('officer.criteria.update', 'traceability'), [
                'category' => 'traceability',
                'criteria' => [
                    ['name' => 'Ketertelusuran kebun', 'weight' => '80', 'active' => '1'],
                ],
            ])
            ->assertRedirect(route('officer.criteria.index'))
            ->assertSessionHasErrors('criteria');

        $this->assertDatabaseCount('assessment_criteria', 0);
    }

    public function test_only_sustainability_officers_can_manage_criteria_and_review_documents(): void
    {
        $management = $this->userWithRole('management');

        $this->actingAs($management)
            ->get(route('officer.criteria.index'))
            ->assertForbidden();

        $this->actingAs($management)
            ->get(route('officer.documents.index'))
            ->assertForbidden();
    }

    public function test_officer_can_open_criteria_settings_and_document_review_pages(): void
    {
        $officer = $this->userWithRole('sustainability_officer');
        $supplier = Supplier::factory()->create(['name' => 'Pemasok Review']);
        $plot = Plot::create(['supplier_id' => $supplier->id, 'name' => 'Plot Review', 'area_ha' => 3]);
        Document::create([
            'plot_id' => $plot->id,
            'status' => 'pending',
            'file_path' => 'legal-documents/'.$supplier->id.'/review.pdf',
        ]);

        $this->actingAs($officer)
            ->get(route('officer.criteria.index'))
            ->assertOk()
            ->assertSee('Kriteria dan Bobot Asesmen')
            ->assertSee('Sustainability')
            ->assertSee('Traceability');

        $this->actingAs($officer)
            ->get(route('officer.documents.index'))
            ->assertOk()
            ->assertSee('Pemasok Review')
            ->assertSee('Setujui')
            ->assertSee('Tolak dokumen');
    }

    public function test_officer_can_approve_a_valid_pending_document(): void
    {
        $officer = $this->userWithRole('sustainability_officer');
        Storage::fake('local');
        $supplier = Supplier::factory()->create();
        $plot = Plot::create(['supplier_id' => $supplier->id, 'name' => 'Kebun Valid', 'area_ha' => 3]);
        $path = 'legal-documents/'.$supplier->id.'/valid.pdf';
        Storage::disk('local')->put($path, 'legal certificate');
        $document = Document::create([
            'plot_id' => $plot->id,
            'status' => 'pending',
            'file_path' => $path,
            'original_filename' => 'valid.pdf',
        ]);

        $this->actingAs($officer)
            ->patch(route('officer.documents.review', $document), [
                'status' => 'approved',
                'review_notes' => 'Dokumen telah diverifikasi.',
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'status' => 'approved',
            'reviewed_by' => $officer->id,
            'review_notes' => 'Dokumen telah diverifikasi.',
        ]);
        $this->assertTrue($supplier->fresh()->hasValidCertification());
    }

    public function test_officer_must_explain_a_rejection_and_rejection_deactivates_supplier(): void
    {
        $officer = $this->userWithRole('sustainability_officer');
        $supplier = Supplier::factory()->create(['status' => Supplier::STATUS_ACTIVE]);
        $plot = Plot::create(['supplier_id' => $supplier->id, 'name' => 'Kebun Ditolak', 'area_ha' => 3]);
        $document = Document::create(['plot_id' => $plot->id, 'status' => 'pending']);

        $this->actingAs($officer)
            ->patch(route('officer.documents.review', $document), ['status' => 'rejected'])
            ->assertSessionHasErrors('review_notes');

        $this->actingAs($officer)
            ->patch(route('officer.documents.review', $document), [
                'status' => 'rejected',
                'review_notes' => 'Sertifikat tidak valid.',
            ])
            ->assertSessionHas('success');

        $this->assertSame(Supplier::STATUS_INACTIVE, $supplier->fresh()->status);
        $this->assertFalse($supplier->fresh()->isActive());
        $this->assertDatabaseHas('supplier_status_logs', [
            'supplier_id' => $supplier->id,
            'user_id' => $officer->id,
            'from_status' => Supplier::STATUS_ACTIVE,
            'to_status' => Supplier::STATUS_INACTIVE,
        ]);
    }

    public function test_expired_document_cannot_be_approved(): void
    {
        $officer = $this->userWithRole('sustainability_officer');
        $supplier = Supplier::factory()->create();
        $plot = Plot::create(['supplier_id' => $supplier->id, 'name' => 'Kebun Kedaluwarsa', 'area_ha' => 3]);
        $document = Document::create([
            'plot_id' => $plot->id,
            'status' => 'pending',
            'expires_at' => today()->subDay(),
        ]);

        $this->actingAs($officer)
            ->patch(route('officer.documents.review', $document), ['status' => 'approved'])
            ->assertSessionHasErrors('status');

        $this->assertSame('pending', $document->fresh()->status);
    }

    public function test_document_without_file_cannot_be_approved(): void
    {
        $officer = $this->userWithRole('sustainability_officer');
        $supplier = Supplier::factory()->create();
        $plot = Plot::create(['supplier_id' => $supplier->id, 'name' => 'Kebun Tanpa Berkas', 'area_ha' => 3]);
        $document = Document::create(['plot_id' => $plot->id, 'status' => 'pending']);

        $this->actingAs($officer)
            ->patch(route('officer.documents.review', $document), ['status' => 'approved'])
            ->assertSessionHasErrors('status');

        $this->assertSame('pending', $document->fresh()->status);
    }

    public function test_management_cannot_activate_supplier_without_valid_documents(): void
    {
        $management = $this->userWithRole('management');
        $supplier = Supplier::factory()->inactive()->create();

        $this->actingAs($management)
            ->patch(route('management.suppliers.status', $supplier), ['status' => 'active'])
            ->assertSessionHas('error');

        $this->assertSame(Supplier::STATUS_INACTIVE, $supplier->fresh()->status);
    }

    public function test_supplier_is_operable_only_when_every_submitted_document_is_approved_and_current(): void
    {
        $supplier = Supplier::factory()->create(['status' => Supplier::STATUS_ACTIVE]);
        $plot = Plot::create(['supplier_id' => $supplier->id, 'name' => 'Kebun Operasional', 'area_ha' => 3]);
        Document::create(['plot_id' => $plot->id, 'status' => 'approved', 'file_path' => 'certificate.pdf']);

        $this->assertTrue($supplier->fresh()->isActive());

        $plot->documents()->firstOrFail()->update(['expires_at' => today()->subDay()]);
        $this->assertFalse($supplier->fresh()->isActive());
        $plot->documents()->firstOrFail()->update(['expires_at' => null]);

        Document::create(['plot_id' => $plot->id, 'status' => 'pending']);
        $this->assertFalse($supplier->fresh()->isActive());
    }
}
