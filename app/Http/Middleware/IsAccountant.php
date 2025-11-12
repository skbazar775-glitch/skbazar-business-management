<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAccountant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('accountant')->check()) {
            return $next($request);
        }

        return redirect()->route('accountant.login')->with('error', 'You must be an accountant to access this page.');
    }
}