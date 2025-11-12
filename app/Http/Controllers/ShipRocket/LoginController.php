<?php

namespace App\Http\Controllers\ShipRocket;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('shiprocket.login');
    }

    public function login(Request $request)
    {
        $response = Http::post('https://apiv2.shiprocket.in/v1/external/auth/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($response->successful()) {
            $data = $response->json();

            \Log::info('Shiprocket login success', [
                'email' => $data['email'],
                'user_id' => $data['id'],
                'token' => $data['token'],
            ]);

            // Store user info in session
            Session::put('shiprocket_user', $data);

            // Store token in cookie (valid for 60 minutes)
            $cookie = cookie('shiprocket_token', $data['token'], 60);

            return redirect()->route('shiprocket.dashboard')
                             ->with('success', 'Logged in to Shiprocket successfully!')
                             ->cookie($cookie);
        } else {
            \Log::error('Shiprocket login failed', [
                'response' => $response->body()
            ]);

            return redirect()->back()->with('error', 'Shiprocket login failed. Please check credentials.');
        }
    }

    public function logout(Request $request)
    {
        $token = $request->cookie('shiprocket_token');

        if (!$token) {
            return redirect()->route('shiprocket.login.form')->with('error', 'You are not logged in.');
        }

        $response = Http::withToken($token)->post('https://apiv2.shiprocket.in/v1/external/auth/logout');

        if ($response->successful()) {
            \Log::info('Shiprocket logout successful', ['response' => $response->json()]);
        } else {
            \Log::warning('Shiprocket logout failed', ['response' => $response->body()]);
        }

        // Forget session and remove cookie
        Session::forget('shiprocket_user');
        $forgetCookie = cookie()->forget('shiprocket_token');

        return redirect()->route('shiprocket.login.form')
                         ->with('success', 'Logged out successfully from Shiprocket.')
                         ->cookie($forgetCookie);
    }
}
