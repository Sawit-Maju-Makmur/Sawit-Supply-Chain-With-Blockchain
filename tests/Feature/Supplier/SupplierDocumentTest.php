<?php

namespace Tests\Feature\Supplier;

use App\Models\Document;
use App\Models\Plot;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SupplierDocumentTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_can_upload_and_download_a_legal_document_for_its_plot(): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['role' => 'pemasok']);
        $supplier = Supplier::factory()->create(['user_id' => $user->id]);
        $plot = Plot::create(['supplier_id' => $supplier->id, 'name' => 'Kebun Saya', 'area_ha' => 2]);

        $this->actingAs($user)
            ->post(route('pemasok.documents.store', $plot), [
                'type' => 'ISPO',
                'expires_at' => today()->addYear()->toDateString(),
                'file' => UploadedFile::fake()->create('sertifikat.pdf', 100, 'application/pdf'),
            ])
            ->assertRedirect(route('pemasok.documents.index'))
            ->assertSessionHas('success');

        $document = Document::query()->sole();
        $this->assertSame('pending', $document->status);
        $this->assertSame('sertifikat.pdf', $document->original_filename);
        Storage::disk('local')->assertExists($document->file_path);

        $this->actingAs($user)
            ->get(route('pemasok.documents.index'))
            ->assertOk()
            ->assertSee('Dokumen Legalitas')
            ->assertSee('ISPO')
            ->assertSee('sertifikat.pdf');

        $this->actingAs($user)
            ->get(route('pemasok.documents.download', $document))
            ->assertDownload('sertifikat.pdf');
    }

    public function test_supplier_cannot_upload_or_download_another_suppliers_document(): void
    {
        Storage::fake('local');
        $user = User::factory()->create(['role' => 'pemasok']);
        Supplier::factory()->create(['user_id' => $user->id]);
        $otherSupplier = Supplier::factory()->create();
        $plot = Plot::create(['supplier_id' => $otherSupplier->id, 'name' => 'Plot Lain', 'area_ha' => 2]);
        $path = 'legal-documents/'.$otherSupplier->id.'/other.pdf';
        Storage::disk('local')->put($path, 'legal certificate');
        $document = Document::create([
            'plot_id' => $plot->id,
            'type' => 'ISPO',
            'file_path' => $path,
            'original_filename' => 'other.pdf',
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->post(route('pemasok.documents.store', $plot), [])
            ->assertNotFound();

        $this->actingAs($user)
            ->get(route('pemasok.documents.download', $document))
            ->assertNotFound();

        $this->assertDatabaseCount('documents', 1);
    }
}
