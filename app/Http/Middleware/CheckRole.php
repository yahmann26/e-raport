<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/unauthorized');
        }

        $user = Auth::user();

        // Cek apakah user memiliki role yang diizinkan
        if (!in_array($user->role, $roles)) {
            return redirect('/unauthorized');
        }

        return $next($request);
    }
}
