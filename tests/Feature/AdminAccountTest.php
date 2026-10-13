<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccountTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
        $this->admin = User::factory()->admin()->create(['password' => 'Old-Password-1']);
        $this->actingAs($this->admin);
    }

    public function test_password_can_be_changed(): void
    {
        $response = $this->from('/admin-panel/account')->put('/admin-panel/account', [
            'current_password' => 'Old-Password-1',
            'password' => 'Brand-New-Pass-2',
            'password_confirmation' => 'Brand-New-Pass-2',
        ]);

        $response->assertRedirect('/admin-panel/account');
        $response->assertSessionHas('status', 'Your password has been changed.');
        $this->assertTrue(Hash::check('Brand-New-Pass-2', $this->admin->fresh()->password));
    }

    public function test_wrong_current_password_is_rejected(): void
    {
        $response = $this->from('/admin-panel/account')->put('/admin-panel/account', [
            'current_password' => 'not-my-password',
            'password' => 'Brand-New-Pass-2',
            'password_confirmation' => 'Brand-New-Pass-2',
        ]);

        $response->assertSessionHasErrors(['current_password' => 'The password is incorrect.']);
        $this->assertTrue(Hash::check('Old-Password-1', $this->admin->fresh()->password));
    }

    public function test_confirmation_must_match(): void
    {
        $response = $this->from('/admin-panel/account')->put('/admin-panel/account', [
            'current_password' => 'Old-Password-1',
            'password' => 'Brand-New-Pass-2',
            'password_confirmation' => 'Different-Pass-3',
        ]);

        $response->assertSessionHasErrors(['password' => 'The password field confirmation does not match.']);
    }

    public function test_weak_password_is_rejected(): void
    {
        $response = $this->from('/admin-panel/account')->put('/admin-panel/account', [
            'current_password' => 'Old-Password-1',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertTrue(Hash::check('Old-Password-1', $this->admin->fresh()->password));
    }

    public function test_new_password_must_differ_from_the_current_one(): void
    {
        $response = $this->from('/admin-panel/account')->put('/admin-panel/account', [
            'current_password' => 'Old-Password-1',
            'password' => 'Old-Password-1',
            'password_confirmation' => 'Old-Password-1',
        ]);

        $response->assertSessionHasErrors(['password' => 'The password field and current password must be different.']);
    }
}
