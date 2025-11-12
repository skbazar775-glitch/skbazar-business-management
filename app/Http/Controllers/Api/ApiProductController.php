<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ApiProductController extends Controller
{
public function index(Request $request)
{
    \Log::info('Product API index() hit', [
        'query' => $request->q,
        'ip' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);
    

    $query = Product::with('category')->latest();

    if ($request->has('q')) {
        $query->where('name', 'like', '%' . $request->q . '%');
    }

    $products = $query->get();

    \Log::info('Product API index() response', [
        'result_count' => $products->count(),
    ]);

    return response()->json([
        'data' => $products->map(function ($product) {
            return [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'name' => $product->name,
                'description' => $product->description,
                'image' => $product->image,
                'price_e' => $product->price_e,
                'price_s' => $product->price_s,
                'price_b' => $product->price_b,
                'price_p' => $product->price_p,
                'status' => $product->status,
                'status_text' => $product->status_text,
                'created_by' => $product->created_by,
                'updated_by' => $product->updated_by,
                'sellin_quantity' => $product->sellin_quantity,
                'sellin_quantity_unit' => $product->sellin_quantity_unit,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        }),
    ]);
}

    public function show($id)
    {
        \Log::info('Product API show() hit', [
            'product_id' => $id,
            'ip' => request()->ip(),
        ]);

        $product = Product::with('category')->findOrFail($id);

        \Log::info('Product API show() response', [
            'product_name' => $product->name,
        ]);

        return response()->json([
            'data' => [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'name' => $product->name,
                'description' => $product->description,
                'image' => $product->image,
                'price_e' => $product->price_e,
                'price_s' => $product->price_s,
                'price_b' => $product->price_b,
                'price_p' => $product->price_p,
                'status' => $product->status,
                'status_text' => $product->status_text,
                'created_by' => $product->created_by,
                'updated_by' => $product->updated_by,
                'sellin_quantity' => $product->sellin_quantity,
                'sellin_quantity_unit' => $product->sellin_quantity_unit,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ],
        ]);
    }

    public function categories(Request $request)
    {
        \Log::info('Category API categories() hit', [
            'query' => $request->q,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $query = Category::query()->latest();

        if ($request->has('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $categories = $query->get();

        \Log::info('Category API categories() response', [
            'result_count' => $categories->count(),
        ]);

        return response()->json([
            'data' => $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'title' => $category->title,
                    'description' => $category->description,
                    'image' => $category->image,
                    'slug' => $category->slug,
                    'created_by' => $category->created_by,
                    'updated_by' => $category->updated_by,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at,
                ];
            }),
        ]);
    }
}