<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MultiAuth
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Imposta l'utente autenticato per il guard trovato
                Auth::shouldUse($guard);
                return $next($request);
            }
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
