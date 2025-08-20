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
            return redirect()->route('profile.edit')
                ->with('error', "Your account isn't linked to any franchisee. Please contact an administrator.");
        }
        return $next($request);
    }
}
