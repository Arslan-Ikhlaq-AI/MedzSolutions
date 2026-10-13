<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function protectedPages(): array
    {
        return [
            'dashboard' => ['/admin-panel'],
            'contact messages' => ['/admin-panel/contact-messages'],
            'consultations' => ['/admin-panel/consultations'],
            'job applications' => ['/admin-panel/job-applications'],
            'affiliates' => ['/admin-panel/affiliates'],
            'blog posts' => ['/admin-panel/posts'],
            'new post' => ['/admin-panel/posts/create'],
            'cookie consents' => ['/admin-panel/cookie-consents'],
            'account' => ['/admin-panel/account'],
        ];
    }

    #[DataProvider('protectedPages')]
    public function test_guest_is_redirected_to_the_admin_login(string $uri): void
    {
        $this->get($uri)->assertRedirect(route('admin.login'));
    }

    #[DataProvider('protectedPages')]
    public function test_signed_in_visitor_without_admin_access_gets_403(string $uri): void
    {
        $this->actingAs(User::factory()->create())->get($uri)->assertForbidden();
    }

    #[DataProvider('protectedPages')]
    public function test_admin_can_open_the_page(string $uri): void
    {
        $this->actingAs(User::factory()->admin()->create())->get($uri)->assertOk();
    }

    public function test_login_page_renders(): void
    {
        $this->get('/admin-panel/login')->assertOk()->assertSee('Admin sign in');
    }

    public function test_admin_signs_in_and_lands_on_the_dashboard(): void
    {
        $admin = User::factory()->admin()->create(['email' => 'boss@example.com', 'password' => 'correct-horse-9']);

        $response = $this->post('/admin-panel/login', ['email' => 'boss@example.com', 'password' => 'correct-horse-9']);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_wrong_password_is_rejected(): void
    {
        User::factory()->admin()->create(['email' => 'boss@example.com', 'password' => 'correct-horse-9']);

        $response = $this->from('/admin-panel/login')->post('/admin-panel/login', ['email' => 'boss@example.com', 'password' => 'wrong']);

        $response->assertRedirect('/admin-panel/login');
        $response->assertSessionHasErrors(['email' => 'These credentials do not match an admin account.']);
        $this->assertGuest();
    }

    public function test_valid_credentials_of_a_non_admin_user_are_rejected(): void
    {
        User::factory()->create(['email' => 'visitor@example.com', 'password' => 'correct-horse-9']);

        $response = $this->from('/admin-panel/login')->post('/admin-panel/login', ['email' => 'visitor@example.com', 'password' => 'correct-horse-9']);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_sixth_login_attempt_within_a_minute_returns_429(): void
    {
        foreach (range(1, 5) as $attempt) {
            $this->post('/admin-panel/login', ['email' => 'boss@example.com', 'password' => 'wrong']);
        }

        $this->post('/admin-panel/login', ['email' => 'boss@example.com', 'password' => 'wrong'])->assertTooManyRequests();
    }

    public function test_signed_in_admin_is_sent_from_the_login_page_to_the_dashboard(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get('/admin-panel/login')->assertRedirect(route('admin.dashboard'));
    }

    public function test_admin_can_sign_out(): void
    {
        $response = $this->actingAs(User::factory()->admin()->create())->post('/admin-panel/logout');

        $response->assertRedirect(route('admin.login'));
        $this->assertGuest();
    }

    public function test_admin_shortcut_url_redirects_to_the_panel(): void
    {
        $this->get('/admin')->assertRedirect('/admin-panel');
    }

    public function test_default_admin_account_from_the_seeder_can_sign_in(): void
    {
        $this->seed(AdminUserSeeder::class);

        $response = $this->post('/admin-panel/login', ['email' => AdminUserSeeder::EMAIL, 'password' => AdminUserSeeder::PASSWORD]);

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_running_the_seeder_again_keeps_a_changed_password(): void
    {
        $this->seed(AdminUserSeeder::class);
        User::where('email', AdminUserSeeder::EMAIL)->firstOrFail()->update(['password' => 'Changed-Pass-77']);

        $this->seed(AdminUserSeeder::class);

        $this->post('/admin-panel/login', ['email' => AdminUserSeeder::EMAIL, 'password' => 'Changed-Pass-77'])->assertRedirect(route('admin.dashboard'));
        $this->assertSame(1, User::where('email', AdminUserSeeder::EMAIL)->count());
    }
}
