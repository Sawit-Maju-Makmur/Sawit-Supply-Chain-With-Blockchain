<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_suppliers(): void
    {
        $response = $this->get('/suppliers');
        $response->assertRedirect('/login');

        $response = $this->get('/suppliers/create');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_without_supplier_sees_empty_state(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/suppliers');

        $response->assertOk();
        $response->assertSee('Profil Pemasok Belum Lengkap');
        $response->assertSee('Lengkapi Profil Pemasok');
    }

    public function test_user_can_view_create_supplier_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/suppliers/create');

        $response->assertOk();
        $response->assertSee('Daftar Profil Pemasok Resmi');
        $response->assertSee('Perusahaan');
        $response->assertSee('Pribadi (Petani Swadaya)');
    }

    public function test_user_can_create_supplier_profile(): void
    {
        $user = User::factory()->create();

        $payload = [
            'type'    => 'Perusahaan',
            'name'    => 'PT Sawit Makmur Jaya',
            'nib'     => '1234567890123',
            'npwp'    => '0123456789012345',
            'address' => 'Jl. Kebun Kelapa Sawit No. 10, Riau',
            'phone'   => '081234567890',
        ];

        $response = $this->actingAs($user)->post('/suppliers', $payload);

        $response->assertRedirect('/suppliers');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('suppliers', [
            'user_id' => $user->id,
            'type'    => 'Perusahaan',
            'name'    => 'PT Sawit Makmur Jaya',
            'status'  => 'Menunggu Verifikasi',
        ]);
    }

    public function test_supplier_creation_fails_when_required_fields_are_missing(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/suppliers', [
            'type'    => '',
            'name'    => '',
            'address' => '',
            'phone'   => '',
        ]);

        $response->assertSessionHasErrors(['type', 'name', 'address', 'phone']);
    }

    public function test_authenticated_user_with_supplier_sees_their_details(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::create([
            'user_id' => $user->id,
            'type'    => 'Pribadi',
            'name'    => 'Pak Budi Petani',
            'nib'     => '9876543210123',
            'npwp'    => '9876543210987654',
            'address' => 'Desa Makmur RT 01 RW 02',
            'phone'   => '089876543210',
            'status'  => 'Menunggu Verifikasi',
        ]);

        $response = $this->actingAs($user)->get('/suppliers');

        $response->assertOk();
        $response->assertSee('Pak Budi Petani');
        $response->assertSee('Pribadi');
        $response->assertSee('9876543210123');
        $response->assertSee('Edit Data');
    }

    public function test_user_can_view_edit_form_for_their_supplier(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::create([
            'user_id' => $user->id,
            'type'    => 'Perusahaan',
            'name'    => 'PT Sawit Berkah',
            'nib'     => '1111222233334',
            'npwp'    => '5555666677778',
            'address' => 'Jl. Jenderal Sudirman',
            'phone'   => '081122334455',
            'status'  => 'Menunggu Verifikasi',
        ]);

        $response = $this->actingAs($user)->get("/suppliers/{$supplier->id}/edit");

        $response->assertOk();
        $response->assertSee('Edit Profil Pemasok');
        $response->assertSee('PT Sawit Berkah');
    }

    public function test_user_can_update_their_supplier_data(): void
    {
        $user = User::factory()->create();
        $supplier = Supplier::create([
            'user_id' => $user->id,
            'type'    => 'Pribadi',
            'name'    => 'Nama Lama',
            'nib'     => '111',
            'npwp'    => '222',
            'address' => 'Alamat Lama',
            'phone'   => '0811111111',
            'status'  => 'Menunggu Verifikasi',
        ]);

        $updatePayload = [
            'type'    => 'Perusahaan',
            'name'    => 'Nama Baru Terverifikasi',
            'nib'     => '9999999999',
            'npwp'    => '8888888888',
            'address' => 'Alamat Baru Lengkap',
            'phone'   => '0822222222',
        ];

        $response = $this->actingAs($user)->put("/suppliers/{$supplier->id}", $updatePayload);

        $response->assertRedirect('/suppliers');
        $response->assertSessionHas('success');

        $supplier->refresh();
        $this->assertSame('Nama Baru Terverifikasi', $supplier->name);
        $this->assertSame('Perusahaan', $supplier->type);
        $this->assertSame('Alamat Baru Lengkap', $supplier->address);
    }

    public function test_user_cannot_edit_or_update_another_users_supplier(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $supplierA = Supplier::create([
            'user_id' => $userA->id,
            'type'    => 'Perusahaan',
            'name'    => 'Supplier A',
            'address' => 'Alamat A',
            'phone'   => '081234567890',
            'status'  => 'Menunggu Verifikasi',
        ]);

        $response = $this->actingAs($userB)->get("/suppliers/{$supplierA->id}/edit");
        $response->assertStatus(403);

        $response = $this->actingAs($userB)->put("/suppliers/{$supplierA->id}", [
            'type'    => 'Perusahaan',
            'name'    => 'Supplier Hack',
            'address' => 'Alamat Hack',
            'phone'   => '089999999999',
        ]);
        $response->assertStatus(403);
    }
}
