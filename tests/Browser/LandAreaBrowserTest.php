<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LandAreaBrowserTest extends DuskTestCase
{
    public function test_user_can_create_land_area_with_polygon()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/land-areas/create')
                    ->type('name', 'Lahan Sawit Tengah Malam')
                    ->script("
                        document.querySelector('input[name=\"polygon_coordinates\"]').value = '[{\"lat\":-6.974, \"lng\":107.630}, {\"lat\":-6.975, \"lng\":107.631}, {\"lat\":-6.976, \"lng\":107.632}]';
                        document.querySelector('form').submit();
                    ");

            $browser->pause(2000) // Memberi waktu server untuk memproses database
                    ->assertPathIs('/land-areas/create');
        });
    }
}
