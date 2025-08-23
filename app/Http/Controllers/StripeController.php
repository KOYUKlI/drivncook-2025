<?php

namespace App\Http\Controllers;

use App\Models\FranchiseApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class StripeController extends Controller
{
    public function checkout(Request $request, int $id)
    {
        $app = FranchiseApplication::where('id', $id)->where('status','accepted')->firstOrFail();
        if ($app->entry_fee_status === 'paid') {
            return back()->with('success','Entry fee already paid.');
        }

        $amount = config('stripe.entry_fee_amount');

        \Stripe\Stripe::setApiKey(config('stripe.secret'));
        $session = \Stripe\Checkout\Session::create([
            'mode' => 'payment',
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => config('stripe.currency','eur'),
                    'unit_amount' => $amount,
                    'product_data' => ['name' => "Droit d'entrée Driv'n Cook"],
                ],
                'quantity' => 1,
            ]],
            'client_reference_id' => (string)$app->id,
            'success_url' => config('stripe.success_url'),
            'cancel_url' => config('stripe.cancel_url'),
        ]);

        $app->update([
            'entry_fee_due' => $amount / 100.0,
            'entry_fee_status' => 'pending',
            'stripe_session_id' => $session->id,
            'stripe_payment_intent' => $session->payment_intent ?? null,
        ]);

        return redirect()->away($session->url);
    }

    public function webhook(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret = config('stripe.webhook_secret');
        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Throwable $e) {
            Log::warning('Stripe webhook signature failed: '.$e->getMessage());
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            /** @var \Stripe\Checkout\Session $session */
            $session = $event->data->object;
            $appId = (int)($session->client_reference_id ?? 0);
            if ($appId > 0) {
                $app = FranchiseApplication::find($appId);
                if ($app && $app->entry_fee_status !== 'paid') {
                    $app->update([
                        'entry_fee_status' => 'paid',
                        'entry_fee_paid_at' => now(),
                        'stripe_payment_intent' => $session->payment_intent ?? $app->stripe_payment_intent,
                    ]);
                    Log::info('Entry fee paid', ['application_id' => $app->id]);
                }
            }
        }

        return response('ok');
    }
}
