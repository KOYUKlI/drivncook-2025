<?php

namespace App\Http\Controllers;

use App\Models\FranchiseApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FranchiseApplicationController extends Controller
{
    public function create(): View
    {
        return view('franchise.apply');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'phone' => ['nullable','string','max:50'],
            'city' => ['nullable','string','max:120'],
            'experience' => ['nullable','string'],
            'motivation' => ['required','string','min:20'],
            // Explicit acceptance of entry fee (50k) and royalty (4%)
            'accept_entry_fee' => ['accepted'],
            'accept_royalty' => ['accepted'],
            'gdpr' => ['accepted'],
        ]);

        // Enforce one pending per email
        $exists = FranchiseApplication::where('email', $data['email'])->where('status','pending')->exists();
        if ($exists) {
            return back()->withInput()->withErrors(['email' => 'Une candidature est déjà en attente pour cet email.']);
        }

        $app = FranchiseApplication::create([
            ...$data,
            'entry_fee_due' => 50000,
            'entry_fee_status' => 'pending',
            'status' => 'pending',
        ]);

        // Send mails (applicant + admin)
    $method = app()->runningUnitTests() ? 'queue' : (config('queue.default') === 'sync' ? 'send' : 'queue');
        try {
            Mail::to($app->email)->{$method}(new \App\Mail\FranchiseApplicationReceived($app));
        } catch (\Throwable $e) {
            // Fallback to sync send if queue failed (e.g., no worker or missing jobs table)
            try { Mail::to($app->email)->send(new \App\Mail\FranchiseApplicationReceived($app)); } catch (\Throwable $e2) {}
        }
        try {
            $adminAddress = config('mail.from.address');
            if ($adminAddress) {
                Mail::to($adminAddress)->{$method}(new \App\Mail\FranchiseApplicationReceived($app, true));
            }
        } catch (\Throwable $e) {
            if (!empty($adminAddress)) {
                try { Mail::to($adminAddress)->send(new \App\Mail\FranchiseApplicationReceived($app, true)); } catch (\Throwable $e2) {}
            }
        }

        return redirect()->route('franchise.apply')->with('success', 'Votre candidature a été envoyée. Nous reviendrons vers vous rapidement.');
    }
}
