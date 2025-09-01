<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        
        // Log successful login
        Log::info('User logged in successfully', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Determine redirect route and welcome message based on user role
        $welcomeMessage = __('auth.login_success');
        $redirectRoute = 'dashboard'; // Default fallback
        
        // Check roles in priority order
        if ($user->hasRole(['admin', 'warehouse', 'fleet', 'tech'])) {
            $redirectRoute = 'bo.dashboard';
            $welcomeMessage = __('auth.login_success');
        } elseif ($user->hasRole('franchisee')) {
            $redirectRoute = 'fo.dashboard';
            $welcomeMessage = __('auth.franchisee_welcome');
            
            // Check if this is the first login (password just set)
            if ($user->created_at && $user->created_at->diffInHours(now()) < 24) {
                $welcomeMessage = __('auth.franchisee_password_setup');
            }
        }

        // Force redirect to correct dashboard based on role, ignore intended URL
        return redirect()->route($redirectRoute)
            ->with('success', $welcomeMessage);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // Log logout
        if ($user) {
            Log::info('User logged out', [
                'user_id' => $user->id,
                'email' => $user->email,
                'ip' => $request->ip(),
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', __('auth.logout_success'));
    }
}
