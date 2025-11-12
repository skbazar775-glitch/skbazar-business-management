<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Models\UpiOrder;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Stock;



class ApiCreateOrder extends Controller
{


public function create(Request $request)
{
    Log::info('Received UPI Order Create Request', [
        'request_data' => $request->all(),
        'ip' => $request->ip(),
    ]);

    $validated = $request->validate([
        'paymentMethod' => 'required|in:online,offline',
        'total_amount' => 'required|numeric|min:0',
        'addressId' => 'required|integer|exists:addresses,id',
        'items' => 'required|array',
        'items.*.id' => 'required|integer',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'customer_mobile' => 'required|numeric',
    ]);

    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $order_id = 'CWSUPI' . time() . rand(11111, 99999);

    return DB::transaction(function () use ($validated, $user, $order_id, $request) {
        foreach ($validated['items'] as $item) {
            $stock = Stock::where('product_id', $item['id'])->first();
            if (!$stock || $stock->stock_quantity < $item['quantity']) {
                throw new \Exception("Insufficient stock for product ID: {$item['id']}");
            }
        }

        $order = Order::create([
            'unique_order_id' => $order_id,
            'user_id' => $user->id,
            'address_id' => $validated['addressId'],
            'total_amount' => $validated['total_amount'],
            'payment_method' => $validated['paymentMethod'],
            'payment_status' => $validated['paymentMethod'] === 'online' ? 0 : 2,
            'status' => 0,
        ]);

        foreach ($validated['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'product_id' => $item['id'],
                'unique_order_id' => $order_id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        $amount = $validated['total_amount'];
        $mobile = $validated['customer_mobile'];

        Session::put('order_id_cookie', $order_id);
        $callbackurl = route('shiprocket.orders.index');

        $payload = [
            'customer_mobile' => $mobile,
            'user_token' => env('USER_TOKEN'),
            'amount' => $amount,
            'order_id' => $order_id,
            'redirect_url' => $callbackurl,
            'remark1' => 'test1',
            'remark2' => 'test2',
        ];

        Log::info('Sending payload to UPI API', [
            'payload' => $payload,
            'api_url' => 'https://upi.clusterwebsolution.com/api/create-order',
        ]);

        try {
            $response = Http::asForm()->post('https://upi.clusterwebsolution.com/api/create-order', $payload);

            Log::info('UPI API Raw Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $jsonResponse = $response->json();

                if (isset($jsonResponse['result']['payment_url'])) {
                    Log::info('Payment URL received', [
                        'payment_url' => $jsonResponse['result']['payment_url']
                    ]);

                    UpiOrder::create([
                        'user_id' => auth()->id(),
                        'order_id' => $order_id,
                        'customer_mobile' => $mobile,
                        'amount' => $amount,
                        'payment_url' => $jsonResponse['result']['payment_url'],
                        'response_json' => $jsonResponse,
                        'is_active' => 0,
                    ]);

                    return response()->json([
                        'success' => true,
                        'payment_url' => $jsonResponse['result']['payment_url'],
                        'message' => 'Redirecting to UPI payment...',
                        'full_response' => $jsonResponse
                    ]);
                } else {
                    Log::error('Payment URL missing from UPI response', [
                        'response_data' => $jsonResponse
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to get payment URL from the response.',
                        'full_response' => $jsonResponse
                    ], 400);
                }
            } else {
                Log::error('UPI API returned error response', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment gateway API request failed.',
                    'status' => $response->status()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::critical('Exception occurred while communicating with UPI API', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    });
}


    /**
     * Check the order status after payment completion.
     */
public function checkOrderStatus(Request $request)
{
    // Get order ID from session
    $orderId = Session::get('order_id_cookie');

    if (!$orderId) {
        return redirect('/fund/fund_request_report')->with([
            'type' => 'error',
            'error_msg' => 'Order ID is missing.'
        ]);
    }

    // API endpoint
    $url = "https://upi.clusterwebsolution.com/api/check-order-status";

    // API payload
    $postData = [
        "user_token" => env('USER_TOKEN'),
        "order_id" => $orderId
    ];

    // Initialize cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

    // Execute cURL
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        curl_close($ch);
        return redirect('/fund/fund_request_report')->with([
            'type' => 'error',
            'error_msg' => 'cURL error: ' . curl_error($ch)
        ]);
    }

    curl_close($ch);

    // Decode JSON response
    $responseData = json_decode($response, true);

    // Log raw response
    \Log::info('Check Order Status Response', $responseData);

    // Handle API response
    if (isset($responseData['status']) && $responseData['status'] === 'COMPLETED') {
        $txnStatus = $responseData['result']['txnStatus'] ?? null;
        $status = $responseData['result']['status'] ?? null;

        if ($status === 'SUCCESS') {
            // ✅ SUCCESS: redirect to myaccount?tab=orders
            return redirect('/myaccount?tab=orders')->with([
                'type' => 'success',
                'error_msg' => 'Transaction Successful.'
            ]);
        } else {
            return redirect('/fund/fund_request_report')->with([
                'type' => 'error',
                'error_msg' => 'Transaction Failed or Pending.'
            ]);
        }
    }

    // Invalid response
    return redirect('/shop')->with([
        'type' => 'error',
        'error_msg' => 'Invalid or unexpected API response.'
    ]);
}


}