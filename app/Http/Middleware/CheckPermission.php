<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Grant access if the user has any of the given permission slugs (pipe-separated).
     */
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (! $user->is_active) {
            auth()->logout();

            return redirect()->route('login')->with('error', 'Your account has been deactivated.');
        }

        foreach (explode('|', $permissions) as $slug) {
            $slug = trim($slug);
            if ($slug !== '' && $user->hasPermission($slug)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized access.');
    }
}
