<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class TwoFactorAuthTest extends TestCase
{
    use RefreshDatabase;

    private Google2FA $google2fa;

    protected function setUp(): void
    {
        parent::setUp();
        $this->google2fa = new Google2FA();
    }

    /**
     * Create a user with sensible defaults for testing.
     */
    private function createUser(array $overrides = []): User
    {
        $role = $overrides['current_role'] ?? 'student';
        return User::factory()->create(array_merge([
            'role' => $role,
            'current_role' => $role,
            'requested_role' => $role,
            'approval_status' => 'approved',
            'email_verified_at' => now(),
        ], $overrides));
    }

    // ===== LOGIN REDIRECT TESTS =====

    public function test_login_redirects_to_2fa_setup_when_not_configured(): void
    {
        $user = $this->createUser([
            'google2fa_secret' => null,
            'two_factor_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('2fa.setup'));
        $this->assertGuest();
        $this->assertEquals($user->id, session('2fa_setup_user_id'));
    }

    public function test_login_redirects_to_2fa_login_when_already_configured(): void
    {
        $secret = $this->google2fa->generateSecretKey();
        $user = $this->createUser([
            'google2fa_secret' => $secret,
            'two_factor_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('2fa.login'));
        $this->assertGuest();
        $this->assertEquals($user->id, session('2fa_user_id'));
    }

    public function test_external_lecturer_bypasses_2fa(): void
    {
        $user = $this->createUser([
            'current_role' => 'external_lecturer',
            'requested_role' => 'external_lecturer',
            'google2fa_secret' => null,
            'two_factor_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    // ===== 2FA SETUP PAGE TESTS =====

    public function test_2fa_setup_page_redirects_without_session(): void
    {
        $response = $this->get(route('2fa.setup'));
        $response->assertRedirect(route('login'));
    }

    public function test_2fa_setup_page_loads_with_valid_session(): void
    {
        $user = $this->createUser([
            'google2fa_secret' => null,
            'two_factor_verified_at' => null,
        ]);

        $response = $this->withSession(['2fa_setup_user_id' => $user->id])
            ->get(route('2fa.setup'));

        $response->assertStatus(200);
        $this->assertGuest();
    }

    // ===== 2FA SETUP VERIFICATION TESTS =====

    public function test_2fa_setup_rejects_invalid_otp(): void
    {
        $secret = $this->google2fa->generateSecretKey();
        $user = $this->createUser([
            'google2fa_secret' => $secret,
            'two_factor_verified_at' => null,
        ]);

        // Generate the valid OTP then modify it to ensure invalidity
        $validOtp = $this->google2fa->getCurrentOtp($secret);
        $invalidOtp = str_pad((string) ((intval($validOtp) + 1) % 1000000), 6, '0', STR_PAD_LEFT);

        $response = $this->withSession(['2fa_setup_user_id' => $user->id])
            ->post(route('2fa.setup.verify'), [
                'one_time_password' => $invalidOtp,
            ]);

        $response->assertSessionHasErrors('one_time_password');
        $this->assertGuest();
        $this->assertNull($user->fresh()->two_factor_verified_at);
    }

    public function test_2fa_setup_accepts_valid_otp_and_authenticates(): void
    {
        $secret = $this->google2fa->generateSecretKey();
        $validOtp = $this->google2fa->getCurrentOtp($secret);
        $user = $this->createUser([
            'google2fa_secret' => $secret,
            'two_factor_verified_at' => null,
        ]);

        $response = $this->withSession(['2fa_setup_user_id' => $user->id])
            ->post(route('2fa.setup.verify'), [
                'one_time_password' => $validOtp,
            ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->two_factor_verified_at);
    }

    public function test_2fa_setup_verify_redirects_without_session(): void
    {
        $response = $this->post(route('2fa.setup.verify'), [
            'one_time_password' => '123456',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    // ===== 2FA LOGIN VERIFICATION TESTS =====

    public function test_2fa_login_page_redirects_without_session(): void
    {
        $response = $this->get(route('2fa.login'));
        $response->assertRedirect(route('login'));
    }

    public function test_2fa_login_rejects_invalid_otp(): void
    {
        $secret = $this->google2fa->generateSecretKey();
        $user = $this->createUser([
            'google2fa_secret' => $secret,
            'two_factor_verified_at' => now(),
        ]);

        $validOtp = $this->google2fa->getCurrentOtp($secret);
        $invalidOtp = str_pad((string) ((intval($validOtp) + 1) % 1000000), 6, '0', STR_PAD_LEFT);

        $response = $this->withSession(['2fa_user_id' => $user->id])
            ->post(route('2fa.login.verify'), [
                'one_time_password' => $invalidOtp,
            ]);

        $response->assertSessionHasErrors('one_time_password');
        $this->assertGuest();
    }

    public function test_2fa_login_accepts_valid_otp_and_authenticates(): void
    {
        $secret = $this->google2fa->generateSecretKey();
        $validOtp = $this->google2fa->getCurrentOtp($secret);
        $user = $this->createUser([
            'google2fa_secret' => $secret,
            'two_factor_verified_at' => now(),
        ]);

        $response = $this->withSession(['2fa_user_id' => $user->id])
            ->post(route('2fa.login.verify'), [
                'one_time_password' => $validOtp,
            ]);

        $response->assertRedirect('/home');
        $this->assertAuthenticatedAs($user);
    }

    public function test_2fa_login_verify_redirects_without_session(): void
    {
        $response = $this->post(route('2fa.login.verify'), [
            'one_time_password' => '123456',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    // ===== MIDDLEWARE PROTECTION TESTS =====

    public function test_authenticated_user_without_2fa_is_redirected_by_middleware(): void
    {
        $user = $this->createUser([
            'google2fa_secret' => null,
            'two_factor_verified_at' => null,
        ]);

        // Even if somehow authenticated without 2FA, middleware should redirect
        $response = $this->actingAs($user)->get('/home');
        $response->assertRedirect(route('2fa.setup'));
    }

    public function test_authenticated_user_with_2fa_can_access_home(): void
    {
        $secret = $this->google2fa->generateSecretKey();
        $user = $this->createUser([
            'google2fa_secret' => $secret,
            'two_factor_verified_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/home');
        // HomeController redirects to role-specific dashboard — 302 means access was granted (not blocked by 2FA middleware)
        $response->assertRedirect();
        $this->assertStringNotContainsString('2fa', $response->headers->get('Location'));
    }
}
