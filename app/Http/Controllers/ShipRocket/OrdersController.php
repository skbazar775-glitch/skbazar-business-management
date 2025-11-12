<?php

namespace App\Http\Controllers\ShipRocket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $token = $request->cookie('shiprocket_token');

        if (!$token) {
            return redirect()->route('shiprocket.login.form')->with('error', 'Please login to continue.');
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->get('https://apiv2.shiprocket.in/v1/external/orders');

            if ($response->successful()) {
                $orders = $response->json()['data'] ?? [];
                return view('shiprocket.orders.index', compact('orders'));
            } else {
                Log::error('ShipRocket API error: ' . $response->body());
                return back()->with('error', 'Failed to fetch orders. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('ShipRocket API exception: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while fetching orders.');
        }
    }

    public function checkServiceability(Request $request, $orderId)
    {
        $token = $request->cookie('shiprocket_token');

        if (!$token) {
            return response()->json(['error' => 'Please login to continue.'], 401);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->get('https://apiv2.shiprocket.in/v1/external/courier/serviceability/', [
                'order_id' => $orderId
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                Log::error('ShipRocket Serviceability API error: ' . $response->body());
                return response()->json(['error' => 'Failed to fetch serviceability details.'], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('ShipRocket Serviceability API exception: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching serviceability details.'], 500);
        }
    }

    public function assignAwb(Request $request)
    {
        $token = $request->cookie('shiprocket_token');

        if (!$token) {
            return response()->json(['error' => 'Please login to continue.'], 401);
        }

        $validated = $request->validate([
            'shipment_id' => 'required|string',
            'courier_id' => 'required|string',
            'status' => 'nullable|string'
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post('https://apiv2.shiprocket.in/v1/external/courier/assign/awb', [
                'shipment_id' => $request->shipment_id,
                'courier_id' => $request->courier_id,
                'status' => $request->status ?? ''
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                return response()->json([
                    'shipment_id' => $responseData['shipment_id'] ?? $request->shipment_id,
                    'courier_id' => $responseData['courier_id'] ?? $request->courier_id,
                    'status' => $responseData['status'] ?? $request->status ?? 'Assigned'
                ]);
            } else {
                Log::error('ShipRocket AWB Assignment API error: ' . $response->body());
                return response()->json(['error' => 'Failed to assign AWB: ' . $response->body()], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('ShipRocket AWB Assignment API exception: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while assigning AWB: ' . $e->getMessage()], 500);
        }
    }

    public function generatePickup(Request $request)
    {
        $token = $request->cookie('shiprocket_token');

        if (!$token) {
            return response()->json(['error' => 'Please login to continue.'], 401);
        }

        $validated = $request->validate([
            'shipment_id' => 'required|array',
            'shipment_id.*' => 'required|string',
            'status' => 'nullable|string|in:retry',
            'pickup_date' => 'nullable|array',
            'pickup_date.*' => 'nullable|date_format:Y-m-d|after_or_equal:today'
        ]);

        try {
            $payload = [
                'shipment_id' => $request->shipment_id
            ];

            if ($request->filled('status')) {
                $payload['status'] = $request->status;
            }

            if ($request->filled('pickup_date')) {
                $payload['pickup_date'] = $request->pickup_date;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json'
            ])->post('https://apiv2.shiprocket.in/v1/external/courier/generate/pickup', $payload);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                Log::error('ShipRocket Pickup API error: ' . $response->body());
                return response()->json(['error' => 'Failed to generate pickup: ' . $response->body()], $response->status());
            }
        } catch (\Exception $e) {
            Log::error('ShipRocket Pickup API exception: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while generating pickup: ' . $e->getMessage()], 500);
        }
    }


    public function generateManifest(Request $request)
    {
        $request->validate(['shipment_id' => 'required|array']);
        $token = $request->cookie('shiprocket_token');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post('https://apiv2.shiprocket.in/v1/external/manifests/generate', [
                'shipment_id' => $request->shipment_id,
            ]);

            $data = $response->json();
            if ($response->successful() && isset($data['manifest_url'])) {
                return response()->json(['manifest_url' => $data['manifest_url']]);
            }
            return response()->json(['error' => $data['message'] ?? 'Failed to generate manifest'], 400);
        } catch (\Exception $e) {
            Log::error('Manifest generation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Server error while generating manifest'], 500);
        }
    }

    public function generateLabel(Request $request)
    {
        $request->validate(['shipment_id' => 'required|array']);
        $token = $request->cookie('shiprocket_token');

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ])->post('https://apiv2.shiprocket.in/v1/external/courier/generate/label', [
                'shipment_id' => $request->shipment_id,
            ]);

            $data = $response->json();
            if ($response->successful() && isset($data['label_url'])) {
                return response()->json(['label_url' => $data['label_url']]);
            }
            return response()->json(['error' => $data['message'] ?? 'Failed to generate label'], 400);
        } catch (\Exception $e) {
            Log::error('Label generation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Server error while generating label'], 500);
        }
    }

    // App/Http/Controllers/ShipRocket/OrdersController.php

public function trackShipment(Request $request, $shipmentId)
{
    $token = $request->cookie('shiprocket_token');

    if (!$token) {
        return response()->json(['error' => 'Please login to continue.'], 401);
    }

    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json'
        ])->get("https://apiv2.shiprocket.in/v1/external/courier/track/shipment/{$shipmentId}");

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['tracking_data']['track_url'])) {
                return response()->json(['track_url' => $data['tracking_data']['track_url']]);
            } else {
                Log::error('ShipRocket Tracking API error: No track_url in response');
                return response()->json(['error' => 'Tracking URL not found.'], 400);
            }
        } else {
            Log::error('ShipRocket Tracking API error: ' . $response->body());
            return response()->json(['error' => 'Failed to fetch tracking details.'], $response->status());
        }
    } catch (\Exception $e) {
        Log::error('ShipRocket Tracking API exception: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while fetching tracking details.'], 500);
    }
}


}