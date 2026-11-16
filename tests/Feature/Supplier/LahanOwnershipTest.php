<?php

namespace Tests\Feature\Supplier;

use App\Models\Lahan;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LahanOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_lahan_is_stored_with_authenticated_users_supplier_id(): void
    {
        $user = User::factory()->create(['role' => 'pemasok', 'name' => 'Budi Petani']);
        $supplier = Supplier::factory()->create(['user_id' => $user->id, 'name' => 'Usaha Tani Budi']);

        $response = $this->actingAs($user)
            ->post(route('lahan.store'), [
                'nama_kebun' => 'Kebun Budi 1',
                'lokasi' => 'Kampar, Riau',
                'latitude' => 0.123456,
                'longitude' => 101.123456,
                'luas_ha' => 5.25,
                'status_lahan' => 'SHM',
            ]);

        $response->assertRedirect(route('lahan.index'))
            ->assertSessionHas('success');

        $lahan = Lahan::where('nama_kebun', 'Kebun Budi 1')->firstOrFail();
        $this->assertSame($supplier->id, $lahan->supplier_id);
        $this->assertSame('Usaha Tani Budi', $lahan->nama_pemasok);
    }

    public function test_pemasok_a_cannot_view_edit_update_or_delete_lahan_of_pemasok_b(): void
    {
        $userA = User::factory()->create(['role' => 'pemasok']);
        $supplierA = Supplier::factory()->create(['user_id' => $userA->id]);

        $userB = User::factory()->create(['role' => 'pemasok']);
        $supplierB = Supplier::factory()->create(['user_id' => $userB->id]);

        $lahanB = Lahan::create([
            'supplier_id' => $supplierB->id,
            'nama_pemasok' => $supplierB->name,
            'nama_kebun' => 'Kebun Milik B',
            'lokasi' => 'Siak',
            'luas_ha' => 10.0,
            'status_lahan' => 'SHM',
        ]);

        // User A coba akses show / edit / update / destroy lahan milik B
        $this->actingAs($userA)->get(route('lahan.show', $lahanB))->assertNotFound();
        $this->actingAs($userA)->get(route('lahan.edit', $lahanB))->assertNotFound();

        $this->actingAs($userA)->put(route('lahan.update', $lahanB), [
            'nama_kebun' => 'Kebun Hack',
            'lokasi' => 'Siak',
            'luas_ha' => 10.0,
            'status_lahan' => 'SHM',
        ])->assertNotFound();

        $this->actingAs($userA)->delete(route('lahan.destroy', $lahanB))->assertNotFound();

        $this->assertDatabaseHas('lahans', [
            'id' => $lahanB->id,
            'nama_kebun' => 'Kebun Milik B',
        ]);
    }

    public function test_pemasok_only_sees_their_own_lahans_in_index(): void
    {
        $userA = User::factory()->create(['role' => 'pemasok']);
        $supplierA = Supplier::factory()->create(['user_id' => $userA->id]);

        $userB = User::factory()->create(['role' => 'pemasok']);
        $supplierB = Supplier::factory()->create(['user_id' => $userB->id]);

        $lahanA = Lahan::create([
            'supplier_id' => $supplierA->id,
            'nama_pemasok' => $supplierA->name,
            'nama_kebun' => 'Kebun Khusus A',
            'lokasi' => 'Kampar',
            'luas_ha' => 5.0,
            'status_lahan' => 'SHM',
        ]);

        $lahanB = Lahan::create([
            'supplier_id' => $supplierB->id,
            'nama_pemasok' => $supplierB->name,
            'nama_kebun' => 'Kebun Rahasia B',
            'lokasi' => 'Siak',
            'luas_ha' => 8.0,
            'status_lahan' => 'SHM',
        ]);

        $this->actingAs($userA)
            ->get(route('lahan.index'))
            ->assertOk()
            ->assertSee('Kebun Khusus A')
            ->assertDontSee('Kebun Rahasia B');
    }
}
