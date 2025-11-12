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
    
    /**
     * Fetch all orders for the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Fetch total ordered quantities per product for the user
            $totalQuantities = OrderItem::where('user_id', $user->id)
                ->groupBy('product_id')
                ->select('product_id', DB::raw('SUM(quantity) as total_ordered_quantity'))
                ->pluck('total_ordered_quantity', 'product_id');

            // Fetch orders with their items and product details
            $orders = Order::where('user_id', $user->id)
                ->with(['orderItems' => function ($query) {
                    $query->select('order_items.*', 'products.name as product_name', 'products.image')
                        ->join('products', 'order_items.product_id', '=', 'products.id');
                }])
                ->select('id', 'unique_order_id', 'total_amount', 'payment_method', 'payment_status', 'status', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($order) use ($totalQuantities) {
                    return [
                        'id' => $order->id,
                        'unique_order_id' => $order->unique_order_id,
                        'total_amount' => number_format($order->total_amount, 2),
                        'payment_method' => $order->payment_method,
                        'payment_status' => $order->paymentStatusText,
                        'status' => $order->statusText,
                        'created_at' => $order->created_at->format('Y-m-d'),
                        'items' => $order->orderItems->map(function ($item) use ($totalQuantities) {
                            return [
                                'product_id' => $item->product_id,
                                'name' => $item->product_name,
                                'quantity' => $item->quantity,
                                'total_ordered_quantity' => $totalQuantities[$item->product_id] ?? 0,
                                'price' => number_format($item->price, 2),
                                'image' => $item->image,
                            ];
                        }),
                    ];
                });

            Log::info('Orders fetched for user', [
                'user_id' => $user->id,
                'order_count' => $orders->count(),
            ]);

            return response()->json([
                'message' => 'Orders fetched successfully',
                'orders' => $orders,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch orders', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
            return response()->json(['error' => 'Failed to fetch orders: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Fetch detailed information for a specific order.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            // Get the authenticated user
            $user = Auth::user();
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Fetch total ordered quantities per product for the user
            $totalQuantities = OrderItem::where('user_id', $user->id)
                ->groupBy('product_id')
                ->select('product_id', DB::raw('SUM(quantity) as total_ordered_quantity'))
                ->pluck('total_ordered_quantity', 'product_id');

            // Fetch the order with its items and product details
            $order = Order::where('id', $id)
                ->where('user_id', $user->id)
                ->with(['orderItems' => function ($query) {
                    $query->select(
                        'order_items.*',
                        'products.name as product_name',
                        'products.image',
                        'products.price_e',
                        'products.price_s',
                        'products.price_b',
                        'products.price_p'
                    )
                        ->join('products', 'order_items.product_id', '=', 'products.id');
                }])
                ->select('id', 'unique_order_id', 'total_amount', 'payment_method', 'payment_status', 'status', 'created_at')
                ->first();

            if (!$order) {
                return response()->json(['error' => 'Order not found or unauthorized'], 404);
            }

            $orderData = [
                'id' => $order->id,
                'unique_order_id' => $order->unique_order_id,
                'total_amount' => number_format($order->total_amount, 2),
                'payment_method' => $order->payment_method,
                'payment_status' => $order->paymentStatusText,
                'status' => $order->statusText,
                'created_at' => $order->created_at->format('Y-m-d'),
                'items' => $order->orderItems->map(function ($item) use ($totalQuantities) {
                    return [
                        'product_id' => $item->product_id,
                        'name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'total_ordered_quantity' => $totalQuantities[$item->product_id] ?? 0,
                        'price' => number_format($item->price, 2),
                        'price_e' => number_format($item->price_e, 2),
                        'price_s' => $item->price_s ? number_format($item->price_s, 2) : null,
                        'price_b' => $item->price_b ? number_format($item->price_b, 2) : null,
                        'price_p' => $item->price_p ? number_format($item->price_p, 2) : null,
                        'image' => $item->image,
                    ];
                }),
            ];

            Log::info('Order details fetched', [
                'order_id' => $order->id,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'message' => 'Order details fetched successfully',
                'order' => $orderData,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to fetch order details', [
                'order_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);
            return response()->json(['error' => 'Failed to fetch order details: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a new order.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
                    'payment_status' => $validated['paymentMethod'] === 'offline' ? 0 : 2,
                    'status' => 0,
                ]);

                // Insert order items and update stock
                foreach ($validated['items'] as $item) {
                    // Create order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'user_id' => $user->id,
                        'product_id' => $item['id'],
                        'unique_order_id' => $uniqueOrderId,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'], // Assumes price corresponds to price_e from Product
                    ]);

                    // Update stock
                    $stock = Stock::where('product_id', $item['id'])->first();
                    $stock->decrement('stock_quantity', $item['quantity']);
                    $stock->updated_by = $user->id;
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