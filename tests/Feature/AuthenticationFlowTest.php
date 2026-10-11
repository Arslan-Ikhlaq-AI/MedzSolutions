<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_a_visitor_can_register_and_reach_the_protected_dashboard(): void
    {
        $response = $this->post('/register', [
            'name' => 'Sample User',
            'email' => 'sample@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'sample@example.com')->firstOrFail();

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('users', ['email' => 'sample@example.com']);
        $this->get('/dashboard')->assertOk()->assertSee('Sample User');
    }

    public function test_a_user_can_sign_in_and_sign_out(): void
    {
        $user = User::factory()->create([
            'email' => 'sample@example.com',
            'password' => 'password123',
        ]);

        $this->post('/login', [
            'email' => 'sample@example.com',
            'password' => 'password123',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);
        $this->post('/logout')->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_invalid_login_credentials_are_rejected(): void
    {
        $this->post('/login', [
            'email' => 'sample@example.com',
            'password' => 'incorrect-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_guests_are_redirected_from_the_dashboard_to_sign_in(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }
}
