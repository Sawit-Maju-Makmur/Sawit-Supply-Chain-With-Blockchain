<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleBasedRedirectTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['sustainability_officer', 'management', 'eksportir_importir', 'pemasok'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
    }

    public function test_officer_redirected_to_officer_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'sustainability_officer']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('officer.dashboard'));
    }

    public function test_management_redirected_to_management_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'management']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('management.dashboard'));
    }

    public function test_eksportir_redirected_to_eksportir_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'eksportir_importir']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('eksportir.dashboard'));
    }

    public function test_pemasok_redirected_to_pemasok_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'pemasok']);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('pemasok.dashboard'));
    }

    public function test_user_without_role_returns_forbidden(): void
    {
        $user = User::factory()->create(['role' => null]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(403);
    }
}
