<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware {
    public function handle(Request $request, Closure $next): Response {
        // Autoriser seulement si l'utilisateur est admin
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            // Rediriger vers l'accueil avec un message d’erreur si non-admin
            return redirect('/')->with('error', 'Access denied.');
        }
        return $next($request);
    }
}
