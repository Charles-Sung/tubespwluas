<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!session()->has('jwt_token')) {
            return redirect()->route('login');
        }

        $user = session('user');
        
        if (!empty($roles) && !in_array($user['role'], $roles)) {
            abort(403, 'Akses Ditolak.');
        }

        return $next($request);
    }
}
