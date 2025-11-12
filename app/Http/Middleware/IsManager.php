<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('manager')->check()) {
            return $next($request);
        }

        return redirect()->route('manager.login')->with('error', 'You must be a manager to access this page.');
    }
}