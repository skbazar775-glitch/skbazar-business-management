<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountantController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('accountant')->check()) {
            return redirect()->route('accountant.dashboard');
        }

        return view('accountant.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('accountant')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('accountant.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        return view('accountant.dashboard', [
            'accountant' => Auth::guard('accountant')->user(),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('accountant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('accountant.login');
    }
}