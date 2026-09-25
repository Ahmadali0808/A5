<?php

namespace Tests\Feature;

use App\Models\Band;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

// UI tests voor het dashboard en de gebruikersgegevens
class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_shows_only_own_bands(): void
    {
        $user = User::factory()->create();
        $mine = Band::factory()->create(['name' => 'Mijn Band']);
        Band::factory()->create(['name' => 'Andere Band']);
        $user->bands()->attach($mine);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSeeText('Mijn Band');
        $response->assertDontSeeText('Andere Band');
    }

    public function test_user_sees_profile_form(): void
    {
        $user = User::factory()->create(['name' => 'Ahmad']);

        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('Ahmad');
    }

    public function test_user_can_change_own_details(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nieuwe Naam',
            'email' => 'nieuw@example.com',
            'password' => 'nieuwwachtwoord',
            'password_confirmation' => 'nieuwwachtwoord',
        ]);

        $response->assertRedirect(route('home'));
        $user->refresh();
        $this->assertSame('Nieuwe Naam', $user->name);
        $this->assertSame('nieuw@example.com', $user->email);
        $this->assertTrue(Hash::check('nieuwwachtwoord', $user->password));
    }

    public function test_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'bezet@example.com']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Naam',
            'email' => 'bezet@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }
}