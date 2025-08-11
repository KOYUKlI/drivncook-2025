<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFranchiseAttached
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if ($user && $user->role === 'franchise' && empty($user->franchise_id)) {
            return redirect()->route('dashboard')
                ->with('error', "Votre compte n'est rattaché à aucun franchisé. Contactez un administrateur.");
        }
        return $next($request);
    }
}
