<?php

namespace App\Http\Controllers\ShipRocket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PickupandManifestController extends Controller
{
    public function index(Request $request)
    {
        // 🧠 Get token from cookie
        $token = $request->cookie('shiprocket_token');

        if (!$token) {
            return redirect()->route('shiprocket.login.form')->with('error', 'Please login to continue.');
        }

        // Fetch shipments from ShipRocket API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->get('https://apiv2.shiprocket.in/v1/external/shipments');

        if ($response->successful()) {
            $shipments = $response->json()['data'] ?? [];
            return view('shiprocket.shiped', compact('shipments'));
        }

        Log::error('ShipRocket API error: ' . $response->body());
        return redirect()->back()->with('error', 'Failed to fetch shipments from ShipRocket.');
    }
}