<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LandAreaBrowserTest extends DuskTestCase
{
    public function test_user_can_create_land_area_with_polygon_coordinates(): void
    {
        $this->artisan('migrate:fresh');

        $this->browse(function (Browser $browser) {
            $browser->visit('/land-areas/create')
                ->waitFor('#coordinates', 10)
                ->type('#name', 'Kebun Dusk Blok A')
                ->script(
                    'document.querySelector("#coordinates").value = JSON.stringify([
                        [-0.5897, 101.3431],
                        [-0.5905, 101.3450],
                        [-0.5920, 101.3420]
                    ]);'
                )
                ->press('#btn-submit')
                ->waitForText('Batas lahan berhasil disimpan.', 10)
                ->assertSee('Batas lahan berhasil disimpan.');
        });

        $this->assertDatabaseHas('land_areas', [
            'name' => 'Kebun Dusk Blok A',
        ]);
    }
}
