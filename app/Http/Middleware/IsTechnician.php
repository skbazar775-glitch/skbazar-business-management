<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsTechnician
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('technician')->check()) {
            return $next($request);
        }

        return redirect()->route('technician.login')->with('error', 'You must be a technician to access this page.');
    }
}