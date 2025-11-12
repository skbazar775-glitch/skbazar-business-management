<?php

namespace App\Http\Controllers\ShipRocket;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderCreateController extends Controller
{
    public function index(Request $request)
    {
        return view('shiprocket.ordercreate');
    }

public function store(Request $request)
{
    // Get token from cookie
    $token = $request->cookie('shiprocket_token');

    if (!$token) {
        Log::channel('shiprocket')->warning('Order creation attempt without token', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
        return redirect()->route('shiprocket.login.form')->with('error', 'Please login to continue.');
    }

    // Get all input data as payload
    $payload = $request->all();

    // Set shipping_is_billing to true by default if not present
    $payload['shipping_is_billing'] = isset($payload['shipping_is_billing']) 
        ? filter_var($payload['shipping_is_billing'], FILTER_VALIDATE_BOOLEAN) 
        : true;

    // Validate required fields
    $validator = \Validator::make($payload, [
        'order_id' => 'required|string',
        'order_date' => 'required|date_format:Y-m-d H:i',
        'pickup_location' => 'required|string',
        'billing_customer_name' => 'required|string',
        'billing_email' => 'required|email',
        'billing_phone' => 'required|string|digits:10',
        'billing_address' => 'required|string',
        'billing_city' => 'required|string',
        'billing_pincode' => 'required|string|digits:6',
        'billing_state' => 'required|string',
        'billing_country' => 'required|string',
        'order_items' => 'required|array',
        'order_items.*.name' => 'required|string',
        'order_items.*.sku' => 'required|string',
        'order_items.*.units' => 'required|integer|min:1',
        'order_items.*.selling_price' => 'required|numeric|min:0',
        'sub_total' => 'required|numeric|min:0',
        'length' => 'required|numeric|min:0',
        'breadth' => 'required|numeric|min:0',
        'height' => 'required|numeric|min:0',
        'weight' => 'required|numeric|min:0',
        // Validate shipping fields only if shipping_is_billing is false
        'shipping_customer_name' => $payload['shipping_is_billing'] ? 'nullable' : 'required|string',
        'shipping_email' => $payload['shipping_is_billing'] ? 'nullable' : 'required|email',
        'shipping_phone' => $payload['shipping_is_billing'] ? 'nullable' : 'required|string|digits:10',
        'shipping_address' => $payload['shipping_is_billing'] ? 'nullable' : 'required|string',
        'shipping_city' => $payload['shipping_is_billing'] ? 'nullable' : 'required|string',
        'shipping_pincode' => $payload['shipping_is_billing'] ? 'nullable' : 'required|string|digits:6',
        'shipping_state' => $payload['shipping_is_billing'] ? 'nullable' : 'required|string',
        'shipping_country' => $payload['shipping_is_billing'] ? 'nullable' : 'required|string',
    ]);

    if ($validator->fails()) {
        Log::channel('shiprocket')->warning('Validation failed', [
            'order_id' => $payload['order_id'] ?? 'N/A',
            'errors' => $validator->errors()->toArray(),
        ]);
        return redirect()->back()->withErrors($validator)->withInput()->with([
            'error' => 'Please correct the form errors and try again.',
            'sent_payload' => $payload,
        ]);
    }

    // If shipping is same as billing, copy billing details to shipping
    if ($payload['shipping_is_billing']) {
        $payload['shipping_customer_name'] = $payload['billing_customer_name'];
        $payload['shipping_last_name'] = $payload['billing_last_name'] ?? '';
        $payload['shipping_address'] = $payload['billing_address'];
        $payload['shipping_address_2'] = $payload['billing_address_2'] ?? '';
        $payload['shipping_city'] = $payload['billing_city'];
        $payload['shipping_pincode'] = $payload['billing_pincode'];
        $payload['shipping_country'] = $payload['billing_country'];
        $payload['shipping_state'] = $payload['billing_state'];
        $payload['shipping_email'] = $payload['billing_email'];
        $payload['shipping_phone'] = $payload['billing_phone'];
    }

    // Log the exact payload being sent
    Log::channel('shiprocket')->debug('API payload to be sent', [
        'order_id' => $payload['order_id'],
        'payload' => $payload,
    ]);

    try {
        // Log before API call
        Log::channel('shiprocket')->debug('Preparing to call Shiprocket API', [
            'order_id' => $payload['order_id'],
            'endpoint' => 'https://apiv2.shiprocket.in/v1/external/orders/create/adhoc',
            'payload' => $this->maskSensitiveData($payload),
        ]);

        // Make API call to Shiprocket
        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post('https://apiv2.shiprocket.in/v1/external/orders/create/adhoc', $payload);

        if ($response->successful()) {
            $responseData = $response->json();
            Log::channel('shiprocket')->info('Order created successfully', [
                'order_id' => $payload['order_id'],
                'response' => $responseData,
            ]);
            return redirect()->back()->with([
                'success' => 'Order created successfully!',
                'response' => $responseData,
                'sent_payload' => $payload,
            ]);
        }

        $errorData = $response->json();
        Log::channel('shiprocket')->error('Shiprocket API call failed', [
            'order_id' => $payload['order_id'],
            'status' => $response->status(),
            'response' => $errorData,
            'headers' => $response->headers(),
            'sent_payload' => $this->maskSensitiveData($payload),
        ]);
        return redirect()->back()->with([
            'error' => 'Failed to create order: ' . ($errorData['message'] ?? 'Unknown error'),
            'api_response' => $errorData,
            'status_code' => $response->status(),
            'sent_payload' => $payload,
        ]);
    } catch (\Exception $e) {
        Log::channel('shiprocket')->error('Shiprocket order creation error', [
            'order_id' => $payload['order_id'],
            'error' => $e->getMessage(),
            'sent_payload' => $this->maskSensitiveData($payload),
        ]);
        return redirect()->back()->with([
            'error' => 'An error occurred: ' . $e->getMessage(),
            'sent_payload' => $payload,
        ]);
    }
}

    public function logs(Request $request)
    {
        $logFile = storage_path('logs/shiprocket.log');
        $logs = file_exists($logFile) ? file($logFile) : [];
        return view('shiprocket.logs', compact('logs'));
    }

    /**
     * Mask sensitive data in logs
     */
    private function maskSensitiveData(array $data): array
    {
        $masked = $data;
        if (isset($masked['billing_email'])) {
            $masked['billing_email'] = substr($masked['billing_email'], 0, 3) . '***@***' . substr($masked['billing_email'], -3);
        }
        if (isset($masked['shipping_email'])) {
            $masked['shipping_email'] = substr($masked['shipping_email'], 0, 3) . '***@***' . substr($masked['shipping_email'], -3);
        }
        if (isset($masked['billing_phone'])) {
            $masked['billing_phone'] = '***' . substr($masked['billing_phone'], -4);
        }
        if (isset($masked['shipping_phone'])) {
            $masked['shipping_phone'] = '***' . substr($masked['shipping_phone'], -4);
        }
        return $masked;
    }
}