<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class AuthRecoveryTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);

        $this->user = User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
        ]);
        $this->user->assignRole('customer');
    }

    public function test_user_can_reset_password_with_valid_token()
    {
        $token = Password::createToken($this->user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => $this->user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertTrue(Hash::check('newpassword', $this->user->refresh()->password));
    }

    public function test_user_cannot_reset_password_with_invalid_token()
    {
        $response = $this->post(route('password.update'), [
            'token' => 'invalid-token',
            'email' => $this->user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertFalse(Hash::check('newpassword', $this->user->refresh()->password));
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $response = $this->actingAs($this->user)->get(route('dashboard.index'));

        $response->assertStatus(200);
    }
}
