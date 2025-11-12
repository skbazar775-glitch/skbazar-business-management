<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('manager')->check()) {
            return redirect()->route('manager.dashboard');
        }

        return $next($request);
    }
}