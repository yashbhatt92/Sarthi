<?php

namespace App\Http\Middleware;

use App\Domain\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        $allowed = collect($roles)->map(fn (string $role) => UserRole::from($role));

        if (!$allowed->contains($user->role)) {
            abort(403, 'Role is not authorized for this endpoint.');
        }

        return $next($request);
    }
}
