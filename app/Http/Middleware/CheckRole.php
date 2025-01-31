<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $roleName): Response
    {
        if (Auth::check() && Auth::user()->role->name == $roleName) {
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
