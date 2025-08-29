<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogAuthenticationAttempts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log authentication attempts
        if ($request->isMethod('post') && $request->is('login')) {
            Log::info('Login attempt', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]);
        }

        $response = $next($request);

        // Log failed authentication attempts
        if ($request->isMethod('post') && $request->is('login') && $response->isRedirection()) {
            $errors = session('errors');
            if ($errors && ($errors->has('email') || $errors->has('password'))) {
                Log::warning('Failed login attempt', [
                    'email' => $request->input('email'),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'errors' => $errors->all(),
                    'timestamp' => now(),
                ]);
            }
        }

        return $response;
    }
}
