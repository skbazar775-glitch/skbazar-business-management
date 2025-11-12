<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TechnicianController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('technician')->check()) {
            return redirect()->route('technician.dashboard');
        }

        return view('technician.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('technician')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('technician.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function dashboard()
    {
        return view('technician.dashboard', [
            'technician' => Auth::guard('technician')->user(),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('technician')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('technician.login');
    }
}