<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    public function handle(Request $request, Closure $next, $userType): Response
    {
        if (Auth::check() && Auth::user()->userType->name == $userType) {
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
