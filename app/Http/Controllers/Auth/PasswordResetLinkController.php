<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if user exists first
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            Log::warning('Password reset link requested for non-existent user', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);
            
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('auth.account_not_found')]);
        }

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            Log::info('Password reset link sent', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);
            
            return back()->with('status', __('auth.reset_link_sent'));
        }

        Log::warning('Failed to send password reset link', [
            'email' => $request->email,
            'status' => $status,
            'ip' => $request->ip(),
        ]);

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __('auth.reset_link_failed')]);
    }
}
