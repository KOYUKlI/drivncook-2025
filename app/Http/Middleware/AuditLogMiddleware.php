<?php

namespace App\Http\Middleware;

use App\Models\AuditLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuditLogMiddleware
{
    /** @var array<string,bool> */
    private array $exclude = [
        'debugbar.*' => true,
        'horizon.*' => true,
        'telescope.*' => true,
        'storage.*' => true,
        'login' => true,
        'logout' => true,
        'password.*' => true,
        'sanctum.*' => true,
        'up' => true,
    ];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only log mutating methods
        if (! in_array($request->method(), ['POST','PUT','PATCH','DELETE'], true)) {
            return $response;
        }

        $route = optional($request->route())->getName() ?? optional($request->route())->uri();
        if ($this->isExcluded($route)) {
            return $response;
        }

        // Minimal payload snapshot (avoid sensitive data)
        $payload = collect($request->except(['password','password_confirmation','_token']))
            ->take(25)
            ->all();

        try {
            AuditLog::create([
                'id' => (string) \Illuminate\Support\Str::ulid(),
                'user_id' => $request->user() ? $request->user()->id : null,
                'route' => $route,
                'method' => $request->method(),
                'action' => $request->route()?->getActionName(),
                'subject_type' => $request->route()?->parameterNames()[0] ?? null,
                'subject_id' => $request->route()?->parameter($request->route()?->parameterNames()[0] ?? '') ?? null,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'meta' => $payload,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Never break the request flow for audit logging
            report($e); // Log the error but don't interrupt the request flow
        }

        return $response;
    }

    private function isExcluded(?string $routeName): bool
    {
        if (! $routeName) return false;
        foreach (array_keys($this->exclude) as $pattern) {
            if (Str::is($pattern, $routeName)) {
                return true;
            }
        }
        return false;
    }
}
