<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_their_profile_successfully(): void
    {
        $user = User::factory()->create([
            'name' => 'Old Name',
            'email' => 'old@example.com',
        ]);

        $response = $this->actingAs($user)->patch('/profile', [
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success', 'Profil berhasil diperbarui.');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'New Name',
            'email' => 'new@example.com',
        ]);
    }

    public function test_user_cannot_use_an_email_already_taken_by_another_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Owner Name',
            'email' => 'owner@example.com',
        ]);

        User::factory()->create([
            'name' => 'Existing User',
            'email' => 'taken@example.com',
        ]);

        $response = $this->actingAs($user)->from('/profile/edit')->patch('/profile', [
            'name' => 'Owner Name',
            'email' => 'taken@example.com',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => 'taken@example.com',
        ]);
    }
}
