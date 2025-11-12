<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ApiOrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'paymentMethod' => 'required|in:online,offline',
                'total_amount' => 'required|numeric|min:0',
                'addressId' => 'required|integer|exists:addresses,id',
                'items' => 'required|array',
                'items.*.id' => 'required|integer',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
            ]);

            // Get the authenticated user
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Generate a unique order ID
            $uniqueOrderId = 'ORD-' . Str::random(10) . '-' . time();

            // Begin a transaction to ensure data consistency
            return DB::transaction(function () use ($validated, $user, $uniqueOrderId) {
                // Check stock availability for each item
                foreach ($validated['items'] as $item) {
                    $stock = Stock::where('product_id', $item['id'])->first();
                    if (!$stock || $stock->stock_quantity < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product ID: {$item['id']}");
                    }
                }

                // Create the order
                $order = Order::create([
                    'unique_order_id' => $uniqueOrderId,
                    'user_id' => $user->id,
                    'address_id' => $validated['addressId'],
                    'total_amount' => $validated['total_amount'],
                    'payment_method' => $validated['paymentMethod'],
                    'payment_status' => $validated['paymentMethod'] === 'offline' ? 0 : 2, // 0 = unpaid for COD, 2 = failed for online
                    'status' => 0, // 0 = pending
                ]);

                // Insert order items and update stock
                foreach ($validated['items'] as $item) {
                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'user_id' => $user->id,
                        'product_id' => $item['id'],
                        'unique_order_id' => $uniqueOrderId,
                        'quantity' => $item['quantity'], // Ensure quantity is saved
                        'price' => $item['price'],
                    ]);

                    // Update stock
                    $stock = Stock::where('product_id', $item['id'])->first();
                    $stock->decrement('stock_quantity', $item['quantity']);
                    $stock->updated_by = $user->id; // Optional: track who updated the stock
                    $stock->save();
                }

                // Log the order creation
                Log::info('Order placed successfully', [
                    'order_id' => $order->id,
                    'unique_order_id' => $uniqueOrderId,
                    'user_id' => $user->id,
                    'total_amount' => $validated['total_amount'],
                    'payment_method' => $validated['paymentMethod'],
                ]);

                return response()->json([
                    'message' => 'Order placed successfully',
                    'order' => [
                        'id' => $order->id,
                        'unique_order_id' => $uniqueOrderId,
                        'total_amount' => $order->total_amount,
                        'payment_method' => $order->payment_method,
                    ],
                ], 201);
            });
        } catch (\Exception $e) {
            Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'request' => $request->all(),
            ]);
            return response()->json(['error' => 'Failed to place order: ' . $e->getMessage()], 500);
        }
    }
}