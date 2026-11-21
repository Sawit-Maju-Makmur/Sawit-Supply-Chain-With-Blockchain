<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_management_report_page_is_available_for_authenticated_user(): void
    {
        $this->withoutVite();
        $response = $this->actingAs(User::factory()->create())
            ->get(route('management.reports.index'));

        $response->assertOk();
        $response->assertSee('Laporan audit');
        $response->assertSee('PBI 17, Sprint 2');
    }

    public function test_report_preview_and_csv_export_are_available(): void
    {
        $this->withoutVite();
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('management.reports.preview', ['report' => 'suppliers']))
            ->assertOk()
            ->assertSee('Pratinjau Laporan pemasok');

        $this->actingAs($user)
            ->get(route('management.reports.excel', ['report' => 'suppliers']))
            ->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

}
