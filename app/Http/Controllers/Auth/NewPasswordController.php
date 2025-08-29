<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Check if user exists first
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            Log::warning('Password reset attempted for non-existent user', [
                'email' => $request->email,
                'ip' => $request->ip(),
            ]);
            
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.user')]);
        }

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
                
                // Log successful password reset
                Log::info('Password reset successful', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'ip' => $request->ip(),
                ]);
            }
        );

        // Determine success message based on user type
        if ($status == Password::PASSWORD_RESET) {
            $successMessage = __('passwords.reset');
            
            if ($user && $user->franchisee) {
                $successMessage = __('passwords.reset_success_franchisee');
            } elseif ($user && $user->email === 'admin@local.test') {
                $successMessage = __('passwords.reset_success_admin');
            } else {
                $successMessage = __('passwords.reset_success_general');
            }
            
            return redirect()->route('login')->with('status', $successMessage);
        }

        // Handle different error cases
        $errorMessage = match($status) {
            Password::INVALID_TOKEN => __('passwords.invalid_token'),
            Password::INVALID_USER => __('passwords.user'),
            default => __($status)
        };

        Log::warning('Password reset failed', [
            'email' => $request->email,
            'status' => $status,
            'ip' => $request->ip(),
        ]);

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => $errorMessage]);
    }
}
