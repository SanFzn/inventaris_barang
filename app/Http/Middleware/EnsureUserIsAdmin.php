<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->role !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Akses hanya untuk admin.'], 403);
            }
            abort(403, 'Akses hanya untuk admin.');
        }

        return $next($request);
    }
}
