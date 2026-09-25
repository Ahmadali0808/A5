<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

// UI test: werkt het inloggen?
class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_view(): void
    {
        $response = $this->get(route('login'));

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');
        $response->assertSeeText('Login');
    }

    public function test_cannot_see_login_view_when_logged_in(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect(route('home'));
    }

    public function test_user_can_log_in(): void
    {
        $user = User::factory()->create(['password' => bcrypt($password = 'i-love-laravel')]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('i-love-laravel')]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'fout-wachtwoord',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}