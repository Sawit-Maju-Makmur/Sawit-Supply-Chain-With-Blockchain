<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Lahan;
use App\Models\LandArea;
use App\Models\Plot;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_supplier_dashboard_shows_only_data_linked_to_the_supplier_account(): void
    {
        $user = User::factory()->create(['role' => 'pemasok']);
        $supplier = Supplier::factory()->create(['user_id' => $user->id]);
        $lahan = Lahan::create([
            'supplier_id' => $supplier->id,
            'nama_pemasok' => $supplier->name,
            'nama_kebun' => 'Kebun Akun Saya',
            'lokasi' => 'Kampar',
            'luas_ha' => 4.5,
            'status_lahan' => 'SHM',
        ]);
        Document::create(['lahan_id' => $lahan->id, 'type' => 'SHM', 'status' => 'pending']);

        $otherSupplier = Supplier::factory()->create();
        $otherLahan = Lahan::create([
            'supplier_id' => $otherSupplier->id,
            'nama_pemasok' => $otherSupplier->name,
            'nama_kebun' => 'Kebun Pemasok Lain',
            'lokasi' => 'Siak',
            'luas_ha' => 8,
            'status_lahan' => 'SHM',
        ]);
        Document::create(['lahan_id' => $otherLahan->id, 'type' => 'SHM', 'status' => 'approved']);

        LandArea::create([
            'user_id' => $user->id,
            'name' => 'Peta Akun Saya',
            'polygon_coordinates' => [[-0.5, 101.3], [-0.6, 101.4], [-0.7, 101.2]],
        ]);
        LandArea::create([
            'user_id' => $otherSupplier->user_id ?? User::factory()->create()->id,
            'name' => 'Peta Akun Lain',
            'polygon_coordinates' => [[-0.5, 101.3], [-0.6, 101.4], [-0.7, 101.2]],
        ]);

        $this->actingAs($user)
            ->get(route('pemasok.dashboard'))
            ->assertOk()
            ->assertViewHas('plotCount', 1)
            ->assertViewHas('landAreaCount', 1)
            ->assertViewHas('documentCount', 1)
            ->assertViewHas('pendingDocumentCount', 1)
            ->assertViewHas('recentDocuments', fn ($documents) => $documents->count() === 1)
            ->assertSee('Kebun Akun Saya')
            ->assertDontSee('Kebun Pemasok Lain')
            ->assertSee('SHM')
            ->assertSee('Peta Akun Saya')
            ->assertDontSee('Peta Akun Lain');
    }

    public function test_officer_dashboard_shows_live_system_counts_and_recent_records(): void
    {
        $officer = User::factory()->create(['role' => 'sustainability_officer']);
        $supplier = Supplier::factory()->create(['name' => 'Pemasok Dashboard']);
        $lahan = Lahan::create([
            'supplier_id' => $supplier->id,
            'nama_pemasok' => $supplier->name,
            'nama_kebun' => 'Plot Dashboard',
            'lokasi' => 'Kampar',
            'luas_ha' => 3,
            'status_lahan' => 'SHM',
        ]);
        Document::create(['lahan_id' => $lahan->id, 'type' => 'SHM', 'status' => 'pending']);
        $operableSupplier = Supplier::factory()->create(['name' => 'Pemasok Bersertifikat']);
        $operableLahan = Lahan::create([
            'supplier_id' => $operableSupplier->id,
            'nama_pemasok' => $operableSupplier->name,
            'nama_kebun' => 'Plot Bersertifikat',
            'lokasi' => 'Kampar',
            'luas_ha' => 2,
            'status_lahan' => 'SHM',
        ]);
        Document::create([
            'lahan_id' => $operableLahan->id,
            'type' => 'SHM',
            'status' => 'approved',
            'file_path' => 'certificate.pdf',
        ]);

        $this->actingAs($officer)
            ->get(route('officer.dashboard'))
            ->assertOk()
            ->assertViewHas('supplierCount', 2)
            ->assertViewHas('activeSupplierCount', 1)
            ->assertViewHas('plotCount', 2)
            ->assertViewHas('documentCount', 2)
            ->assertViewHas('pendingDocumentCount', 1)
            ->assertSee('Pemasok Dashboard')
            ->assertSee('SHM');
    }

    public function test_exporter_dashboard_shows_live_document_statuses(): void
    {
        $exporter = User::factory()->create(['role' => 'eksportir_importir']);
        $supplier = Supplier::factory()->create(['name' => 'Pemasok Ekspor']);
        $lahan = Lahan::create([
            'supplier_id' => $supplier->id,
            'nama_pemasok' => $supplier->name,
            'nama_kebun' => 'Plot Ekspor',
            'lokasi' => 'Kampar',
            'luas_ha' => 6,
            'status_lahan' => 'SHM',
        ]);
        Document::create(['lahan_id' => $lahan->id, 'type' => 'SHM', 'status' => 'rejected']);

        $this->actingAs($exporter)
            ->get(route('eksportir.dashboard'))
            ->assertOk()
            ->assertViewHas('documentCount', 1)
            ->assertViewHas('rejectedDocumentCount', 1)
            ->assertSee('Pemasok Ekspor')
            ->assertSee('Ditolak');
    }
}
