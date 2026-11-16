<?php

namespace Tests\Feature\Supplier;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupplierProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_automatically_creates_supplier_profile(): void
    {
        $response = $this->post('/register', [
            'name' => 'Petani Baru',
            'email' => 'petanibaru@sawit.test',
            'phone' => '081234567890',
            'address' => 'Jl. Sawit Sejahtera No. 10',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');

        $user = User::where('email', 'petanibaru@sawit.test')->firstOrFail();
        $this->assertDatabaseHas('suppliers', [
            'user_id' => $user->id,
            'name' => 'Petani Baru',
            'email' => 'petanibaru@sawit.test',
            'status' => Supplier::STATUS_DRAFT,
            'type' => 'mandiri',
        ]);
    }

    public function test_pemasok_can_view_and_update_their_business_profile(): void
    {
        $user = User::factory()->create(['role' => 'pemasok']);
        $supplier = Supplier::factory()->create([
            'user_id' => $user->id,
            'name' => 'Nama Usaha Lama',
            'type' => 'mandiri',
            'status' => Supplier::STATUS_ACTIVE,
        ]);

        $this->actingAs($user)
            ->get(route('pemasok.profile.edit'))
            ->assertOk()
            ->assertSee('Profil Usaha Pemasok')
            ->assertSee('Nama Usaha Lama');

        $response = $this->actingAs($user)
            ->put(route('pemasok.profile.update'), [
                'type' => 'plasma',
                'name' => 'Koperasi Sawit Maju',
                'nik' => '1234567890123456',
                'npwp' => '01.234.567.8-901.000',
                'nib' => '9120001234567',
                'regency' => 'Siak',
                'phone' => '081198765432',
                'email' => 'sawitmaju@sawit.test',
                'address' => 'Jl. Lintas Siak Km. 12',
            ]);

        $response->assertRedirect(route('pemasok.profile.edit'))
            ->assertSessionHas('success');

        $supplier->refresh();
        $this->assertSame('plasma', $supplier->type);
        $this->assertSame('Koperasi Sawit Maju', $supplier->name);
        $this->assertSame('9120001234567', $supplier->nib);
        $this->assertSame('Siak', $supplier->regency);
    }

    public function test_pemasok_cannot_change_status_or_deactivation_reason(): void
    {
        $user = User::factory()->create(['role' => 'pemasok']);
        $supplier = Supplier::factory()->create([
            'user_id' => $user->id,
            'name' => 'Petani Swadaya',
            'status' => Supplier::STATUS_DRAFT,
            'deactivation_reason' => null,
        ]);

        $this->actingAs($user)
            ->put(route('pemasok.profile.update'), [
                'type' => 'mandiri',
                'name' => 'Petani Swadaya Update',
                'status' => 'active', // Percobaan mengubah status
                'deactivation_reason' => 'Alasan palsu', // Percobaan manipulasi
            ]);

        $supplier->refresh();
        $this->assertSame(Supplier::STATUS_DRAFT, $supplier->status);
        $this->assertNull($supplier->deactivation_reason);
    }

    public function test_non_pemasok_cannot_access_supplier_profile(): void
    {
        $officer = User::factory()->create(['role' => 'sustainability_officer']);

        $this->actingAs($officer)
            ->get(route('pemasok.profile.edit'))
            ->assertForbidden();

        $this->actingAs($officer)
            ->put(route('pemasok.profile.update'), [
                'type' => 'mandiri',
                'name' => 'Manipulasi',
            ])
            ->assertForbidden();
    }
}
