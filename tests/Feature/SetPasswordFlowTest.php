<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class SetPasswordFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_signed_set_password_allows_setting_password_and_redirects_by_role(): void
    {
        $user = User::factory()->create([
            'role' => 'franchise',
            'password' => bcrypt('temp'),
            'email_verified_at' => null,
        ]);

        $url = URL::signedRoute('password.set', ['id' => $user->id], now()->addMinutes(30));

        $get = $this->get($url);
        $get->assertOk();

        $post = $this->post($url, [
            'id' => (string)$user->id,
            'password' => 'new-strong-pass',
            'password_confirmation' => 'new-strong-pass',
        ]);
        $post->assertRedirect(route('franchise.dashboard'));

        $user->refresh();
        $this->assertNotNull($user->email_verified_at);
    }

    public function test_set_password_rejects_expired_or_used_link(): void
    {
        $user = User::factory()->create([
            'role' => 'franchise',
            'password' => bcrypt('temp'),
            'email_verified_at' => now(), // already used
        ]);

        $url = URL::signedRoute('password.set', ['id' => $user->id], now()->addMinutes(30));

        $resp = $this->get($url);
        $resp->assertStatus(410);
    }
}
