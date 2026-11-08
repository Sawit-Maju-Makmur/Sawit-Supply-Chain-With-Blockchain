<?php

namespace Tests\Feature\Management;

use App\Models\Document;
use App\Models\Plot;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierManagementTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    public function test_management_melihat_daftar_pemasok(): void
    {
        Supplier::factory()->create(['name' => 'Koperasi Tani Makmur']);

        $this->actingAs($this->userWithRole('management'))
            ->get(route('management.suppliers.index'))
            ->assertOk()
            ->assertSee('Koperasi Tani Makmur');
    }

    public function test_management_dashboard_dapat_diakses(): void
    {
        $this->actingAs($this->userWithRole('management'))
            ->get(route('management.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard Management');
    }

    public function test_pencarian_nama(): void
    {
        Supplier::factory()->create(['name' => 'Koperasi Tani Makmur']);
        Supplier::factory()->create(['name' => 'PT Sawit Lestari']);

        $this->actingAs($this->userWithRole('management'))
            ->get(route('management.suppliers.index', ['q' => 'Makmur']))
            ->assertSee('Koperasi Tani Makmur')
            ->assertDontSee('PT Sawit Lestari');
    }

    public function test_filter_tipe_dan_status(): void
    {
        Supplier::factory()->create(['name' => 'Pemasok Plasma Aktif', 'type' => 'plasma']);
        Supplier::factory()->inactive()->create(['name' => 'Pemasok Perusahaan Nonaktif', 'type' => 'perusahaan']);

        $this->actingAs($this->userWithRole('management'))
            ->get(route('management.suppliers.index', ['type' => 'plasma']))
            ->assertSee('Pemasok Plasma Aktif')
            ->assertDontSee('Pemasok Perusahaan Nonaktif');

        $this->actingAs($this->userWithRole('management'))
            ->get(route('management.suppliers.index', ['status' => 'inactive']))
            ->assertSee('Pemasok Perusahaan Nonaktif')
            ->assertDontSee('Pemasok Plasma Aktif');
    }

    public function test_pagination_10_data_per_halaman(): void
    {
        Supplier::factory()->count(12)->create();

        $this->actingAs($this->userWithRole('management'))
            ->get(route('management.suppliers.index'))
            ->assertViewHas('suppliers', fn ($p) => $p->count() === 10 && $p->total() === 12);
    }

    public function test_detail_menampilkan_lahan_dan_ringkasan_dokumen(): void
    {
        $s = Supplier::factory()->create();
        $plot = Plot::create(['supplier_id' => $s->id, 'name' => 'Lahan Blok A', 'area_ha' => 4.2]);
        Document::create(['plot_id' => $plot->id, 'status' => 'approved']);
        Document::create(['plot_id' => $plot->id, 'status' => 'approved']);
        Document::create(['plot_id' => $plot->id, 'status' => 'rejected']);

        $this->actingAs($this->userWithRole('management'))
            ->get(route('management.suppliers.show', $s))
            ->assertOk()
            ->assertSee('Lahan Blok A')
            ->assertSee('Disetujui 2')
            ->assertSee('Ditolak 1');
    }

    public function test_menonaktifkan_tanpa_alasan_ditolak(): void
    {
        $s = Supplier::factory()->create();

        $this->actingAs($this->userWithRole('management'))
            ->patch(route('management.suppliers.status', $s), ['status' => 'inactive'])
            ->assertSessionHasErrors('reason');

        $this->assertSame('active', $s->fresh()->status);
    }

    public function test_menonaktifkan_dengan_alasan_tercatat_di_log(): void
    {
        $mgmt = $this->userWithRole('management');
        $s = Supplier::factory()->create();

        $this->actingAs($mgmt)
            ->patch(route('management.suppliers.status', $s), ['status' => 'inactive', 'reason' => 'Dokumen tidak valid'])
            ->assertSessionHas('success');

        $s->refresh();
        $this->assertSame('inactive', $s->status);
        $this->assertSame('Dokumen tidak valid', $s->deactivation_reason);
        $this->assertDatabaseHas('supplier_status_logs', [
            'supplier_id' => $s->id,
            'user_id' => $mgmt->id,
            'from_status' => 'active',
            'to_status' => 'inactive',
            'reason' => 'Dokumen tidak valid',
        ]);
    }

    public function test_mengaktifkan_kembali_tanpa_alasan_berhasil(): void
    {
        $s = Supplier::factory()->inactive()->create();

        $this->actingAs($this->userWithRole('management'))
            ->patch(route('management.suppliers.status', $s), ['status' => 'active'])
            ->assertSessionHas('success');

        $this->assertTrue($s->fresh()->isActive());
    }

    public function test_so_hanya_bisa_melihat(): void
    {
        $s = Supplier::factory()->create();
        $so = $this->userWithRole('sustainability_officer');

        $this->actingAs($so)->get(route('management.suppliers.index'))->assertOk();
        $this->actingAs($so)->get(route('management.suppliers.show', $s))->assertOk();
        $this->actingAs($so)->patch(route('management.suppliers.status', $s), ['status' => 'inactive', 'reason' => 'x'])->assertForbidden();
        $this->actingAs($so)->get(route('management.suppliers.export'))->assertForbidden();
    }

    public function test_pemasok_tidak_bisa_mengakses(): void
    {
        $this->actingAs($this->userWithRole('pemasok'))
            ->get(route('management.suppliers.index'))
            ->assertForbidden();
    }

    public function test_tamu_diarahkan_ke_login(): void
    {
        $this->get(route('management.suppliers.index'))->assertRedirect();
    }

    public function test_export_mengikuti_filter(): void
    {
        Supplier::factory()->create(['name' => 'Koperasi Tani Makmur']);
        Supplier::factory()->create(['name' => 'PT Sawit Lestari']);

        $response = $this->actingAs($this->userWithRole('management'))
            ->get(route('management.suppliers.export', ['q' => 'Makmur']));

        $response->assertOk();
        $csv = $response->streamedContent();
        $this->assertStringContainsString('Koperasi Tani Makmur', $csv);
        $this->assertStringNotContainsString('PT Sawit Lestari', $csv);
    }
}

