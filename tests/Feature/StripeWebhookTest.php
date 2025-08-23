<?php

namespace Tests\Feature;

use App\Models\FranchiseApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    private function fakeStripeSignature(string $payload, string $secret): string
    {
        $timestamp = time();
        $signedPayload = $timestamp . '.' . $payload;
        $sig = hash_hmac('sha256', $signedPayload, $secret);
        return "t={$timestamp},v1={$sig}";
    }

    public function test_checkout_session_completed_marks_paid(): void
    {
        $app = FranchiseApplication::factory()->create([
            'status' => 'accepted',
            'entry_fee_status' => 'pending',
        ]);

        $payload = json_encode([
            'id' => 'evt_test_1',
            'object' => 'event',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_123',
                    'object' => 'checkout.session',
                    'client_reference_id' => (string)$app->id,
                    'payment_intent' => 'pi_test_123',
                ],
            ],
        ], JSON_UNESCAPED_SLASHES);

        $secret = 'whsec_test_123';
        Config::set('stripe.webhook_secret', $secret);
        $sig = $this->fakeStripeSignature($payload, $secret);

        $resp = $this->call('POST', '/stripe/webhook', [], [], [], [
            'HTTP_Stripe-Signature' => $sig,
            'CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json',
        ], $payload);
        $resp->assertOk();

        $app->refresh();
        $this->assertSame('paid', $app->entry_fee_status);
        $this->assertNotNull($app->entry_fee_paid_at);
        $this->assertSame('pi_test_123', $app->stripe_payment_intent);
    }

    public function test_invalid_signature_is_rejected(): void
    {
        $payload = json_encode([
            'id' => 'evt_test_2',
            'object' => 'event',
            'type' => 'checkout.session.completed',
            'data' => [ 'object' => [ 'object' => 'checkout.session', 'client_reference_id' => '0' ] ],
        ], JSON_UNESCAPED_SLASHES);

        Config::set('stripe.webhook_secret', 'whsec_real');
        $sig = $this->fakeStripeSignature($payload, 'wrong');

        $resp = $this->call('POST', '/stripe/webhook', [], [], [], [
            'HTTP_Stripe-Signature' => $sig,
            'CONTENT_TYPE' => 'application/json',
            'Accept' => 'application/json',
        ], $payload);
        $resp->assertStatus(400);
    }
}
