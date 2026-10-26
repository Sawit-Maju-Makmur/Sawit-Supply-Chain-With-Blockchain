<?php

namespace Tests\Feature;

use App\Models\LandArea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandAreaTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_page_can_be_rendered(): void
    {
        $response = $this->get(route('land-areas.create'));

        $response->assertStatus(200);
        $response->assertViewIs('land_areas.create');
        $response->assertSee('leaflet.css', false);
        $response->assertSee('leaflet.js', false);
        $response->assertSee('id="map"', false);
        $response->assertSee('name="polygon_coordinates"', false);
        $response->assertSee('id="coordinates"', false);
        $response->assertSee('name="name"', false);
    }

    public function test_validation_fails_when_fields_are_missing(): void
    {
        $response = $this->from(route('land-areas.create'))
            ->post(route('land-areas.store'), []);

        $response->assertRedirect(route('land-areas.create'));
        $response->assertSessionHasErrors(['name', 'polygon_coordinates']);
    }

    public function test_validation_fails_when_coordinates_is_not_valid_json(): void
    {
        $response = $this->from(route('land-areas.create'))
            ->post(route('land-areas.store'), [
                'name' => 'Kebun Sawit Blok A',
                'polygon_coordinates' => 'invalid-json-format',
            ]);

        $response->assertRedirect(route('land-areas.create'));
        $response->assertSessionHasErrors(['polygon_coordinates']);
    }

    public function test_validation_fails_when_polygon_has_fewer_than_three_points(): void
    {
        $response = $this->from(route('land-areas.create'))
            ->post(route('land-areas.store'), [
                'name' => 'Kebun Sawit Blok A',
                'polygon_coordinates' => json_encode([
                    [-0.5897, 101.3431],
                    [-0.5900, 101.3440],
                ]),
            ]);

        $response->assertRedirect(route('land-areas.create'));
        $response->assertSessionHasErrors(['polygon_coordinates']);
    }

    public function test_can_store_land_area_with_valid_data(): void
    {
        $user = User::factory()->create();

        $coordinates = [
            [-0.5897, 101.3431],
            [-0.5905, 101.3450],
            [-0.5920, 101.3420],
        ];

        $response = $this->actingAs($user)
            ->post(route('land-areas.store'), [
                'name' => 'Kebun Sawit Inti 1',
                'polygon_coordinates' => json_encode($coordinates),
            ]);

        $response->assertRedirect(route('land-areas.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('land_areas', [
            'user_id' => $user->id,
            'name' => 'Kebun Sawit Inti 1',
        ]);

        $landArea = LandArea::first();
        $this->assertNotNull($landArea);
        $this->assertEquals($coordinates, $landArea->polygon_coordinates);
    }

    public function test_guest_can_store_land_area_using_fallback_user_id(): void
    {
        $user = User::factory()->create(['id' => 1]);

        $coordinates = [
            [-0.5897, 101.3431],
            [-0.5905, 101.3450],
            [-0.5920, 101.3420],
        ];

        $response = $this->post(route('land-areas.store'), [
            'name' => 'Kebun Tamu',
            'polygon_coordinates' => json_encode($coordinates),
        ]);

        $response->assertRedirect(route('land-areas.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('land_areas', [
            'user_id' => 1,
            'name' => 'Kebun Tamu',
        ]);
    }
}
