<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsStaff
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('staff')->check()) {
            return $next($request);
        }

        return redirect()->route('staff.login')->with('error', 'You must be a staff member to access this page.');
    }
}