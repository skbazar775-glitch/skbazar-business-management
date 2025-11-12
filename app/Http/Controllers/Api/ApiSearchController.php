<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiSearchController extends Controller
{
    /**
     * Search products by name, excluding out-of-stock and inactive products, and include related in-stock products from the same categories
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $searchTerm = $request->query('q', '');

        // Validate search term
        if (empty($searchTerm)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Search term is required',
                'data' => []
            ], 400);
        }

        // Search products by name, excluding inactive (status = 1) and out-of-stock (status = 2) products
        $products = Product::query()
            ->where('name', 'LIKE', "%{$searchTerm}%")
            ->whereNotIn('status', [1, 2])
            ->whereNotNull('category_id') // Ensure category_id exists
            ->with('category')
            ->get();

        // If no products found, return empty response
        if ($products->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'No products found',
                'data' => [
                    'products' => [],
                    'related_products' => []
                ]
            ], 200);
        }

        // Get related products from the same categories, excluding inactive, out-of-stock, and already matched products
        $categoryIds = $products->pluck('category_id')->unique()->filter()->toArray();
        
        $relatedProducts = Product::query()
            ->whereIn('category_id', $categoryIds)
            ->whereNotIn('id', $products->pluck('id'))
            ->whereNotIn('status', [1, 2])
            ->whereNotNull('category_id')
            ->with('category')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => [
                'products' => $products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'image' => $product->image,
                        'price_e' => $product->price_e,
                        'price_s' => $product->price_s,
                        'price_b' => $product->price_b,
                        'price_p' => $product->price_p,
                        'status' => $product->status_text ?? 'Available',
                        'category' => $product->category ? [
                            'id' => $product->category->id,
                            'title' => $product->category->title,
                            'slug' => $product->category->slug
                        ] : null,
                        'sellinQUANTITY' => $product->sellin_quantity,
                        'sellinQUANTITYunit' => $product->sellin_quantity_unit
                    ];
                }),
                'related_products' => $relatedProducts->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'description' => $product->description,
                        'image' => $product->image,
                        'price_e' => $product->price_e,
                        'price_s' => $product->price_s,
                        'price_b' => $product->price_b,
                        'price_p' => $product->price_p,
                        'status' => $product->status_text ?? 'Available',
                        'category' => $product->category ? [
                            'id' => $product->category->id,
                            'title' => $product->category->title,
                            'slug' => $product->category->slug
                        ] : null,
                        'sellinQUANTITY' => $product->sellin_quantity,
                        'sellinQUANTITYunit' => $product->sellin_quantity_unit
                    ];
                })
            ]
        ], 200);
    }
}