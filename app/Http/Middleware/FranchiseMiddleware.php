<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class FranchiseMiddleware {
    public function handle(Request $request, Closure $next): Response {
        // Autoriser seulement les utilisateurs franchisés
        if (!Auth::check() || Auth::user()->role !== 'franchise') {
            return redirect('/')->with('error', 'Access denied.');
        }
        return $next($request);
    }
}
