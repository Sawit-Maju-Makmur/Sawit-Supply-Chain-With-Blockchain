<?php

namespace Tests\Feature;

use App\Models\Lahan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LahanPolygonTest extends TestCase
{
    use RefreshDatabase;

    public function test_lahan_form_renders_polygon_map(): void
    {
        $this->get(route('lahan.create'))
            ->assertOk()
            ->assertSee('polygon-map');
    }

    public function test_lahan_can_be_saved_with_polygon_coordinates(): void
    {
        $this->post(route('lahan.store'), [
            'nama_pemasok' => 'Petani Sawit',
            'nama_kebun' => 'Kebun Utama',
            'lokasi' => 'Riau',
            'luas_ha' => 12.5,
            'status_lahan' => 'SHM',
            'polygon_coordinates' => '[[-0.5,101.3],[-0.6,101.4],[-0.7,101.2]]',
        ])->assertRedirect(route('lahan.index'));

        $this->assertSame(
            [[-0.5, 101.3], [-0.6, 101.4], [-0.7, 101.2]],
            Lahan::query()->firstOrFail()->polygon_coordinates
        );
    }

    public function test_lahan_can_still_be_saved_without_polygon_coordinates(): void
    {
        $this->post(route('lahan.store'), [
            'nama_pemasok' => 'Petani Sawit',
            'nama_kebun' => 'Kebun Utama',
            'lokasi' => 'Riau',
            'luas_ha' => 12.5,
            'status_lahan' => 'SHM',
        ])->assertRedirect(route('lahan.index'));

        $this->assertNull(Lahan::query()->firstOrFail()->polygon_coordinates);
    }

    public function test_polygon_requires_at_least_three_valid_points(): void
    {
        $this->from(route('lahan.create'))
            ->post(route('lahan.store'), [
                'nama_pemasok' => 'Petani Sawit',
                'nama_kebun' => 'Kebun Utama',
                'lokasi' => 'Riau',
                'luas_ha' => 12.5,
                'status_lahan' => 'SHM',
                'polygon_coordinates' => '[[-0.5,101.3],[-0.6,101.4]]',
            ])
            ->assertRedirect(route('lahan.create'))
            ->assertSessionHasErrors('polygon_coordinates');

        $this->assertDatabaseCount('lahans', 0);
    }

    public function test_polygon_rejects_coordinates_outside_latitude_and_longitude_ranges(): void
    {
        $this->from(route('lahan.create'))
            ->post(route('lahan.store'), [
                'nama_pemasok' => 'Petani Sawit',
                'nama_kebun' => 'Kebun Utama',
                'lokasi' => 'Riau',
                'luas_ha' => 12.5,
                'status_lahan' => 'SHM',
                'polygon_coordinates' => '[[91,101.3],[-0.6,101.4],[-0.7,101.2]]',
            ])
            ->assertRedirect(route('lahan.create'))
            ->assertSessionHasErrors('polygon_coordinates');

        $this->assertDatabaseCount('lahans', 0);
    }
}
