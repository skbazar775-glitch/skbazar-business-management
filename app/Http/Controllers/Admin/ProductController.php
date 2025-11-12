<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Stock;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        
        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        $products = $query->latest()->paginate(10);

        return view('admin.inventory.products.index', compact('products'));
    }
// Toggle product status
public function toggleStatus(Request $request, $id)
{
    // Find product or throw 404
    $product = Product::findOrFail($id);
    
    // Set status: true (checked) = 1 (active), false (unchecked) = 0 (inactive)
    $product->status = $request->input('status') ? 1 : 0;
    $product->save();

    // Return JSON with updated status
    return response()->json([
        'success' => true,
        'status' => $product->status,
        'status_text' => $product->status_text,
        'status_class' => 'status-' . strtolower($product->status_text)
    ]);
}
    public function create()
    {
        $categories = Category::all();
        return view('admin.inventory.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'price_e' => 'nullable|numeric|min:0',
            'price_s' => 'required|numeric|min:0',
            'price_b' => 'required|numeric|min:0',
            'status' => 'required|in:0,1,2,3,4,5',
            'sellin_quantity' => 'nullable|numeric|min:0',
            'sellin_quantity_unit' => 'required|in:g,kg,lit,ml,piece,inch,m,dozen,packet,box,unit',
        ]);

        $product = new Product();
        $product->fill($validated);
        $product->price_p = $validated['price_s'] - $validated['price_b'];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '-' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();

            // Save directly to public_html/public/uploaded/products
            $destinationPath = public_path('uploaded/products');
            $file->move($destinationPath, $filename);

            // Save filename in DB
            $product->image = $filename;
        }

        $product->save();
        
        // Insert product_id into stocks table
        Stock::create([
            'product_id' => $product->id,
        ]);
        
        // Redirect to index instead of back
        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.inventory.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'price_e' => 'nullable|numeric|min:0',
            'price_s' => 'required|numeric|min:0',
            'price_b' => 'required|numeric|min:0',
            'status' => 'required|in:0,1,2,3,4,5',
            'sellin_quantity' => 'nullable|numeric|min:0',
            'sellin_quantity_unit' => 'required|in:g,kg,lit,ml,piece,inch,m,dozen,packet,box,unit',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            $oldPath = public_path('uploaded/products/' . $product->image);
            if ($product->image && file_exists($oldPath)) {
                unlink($oldPath);
            }

            // Upload new image
            $file = $request->file('image');
            $filename = time() . '-' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();

            // Save file to public_html/public/uploaded/products
            $destinationPath = public_path('uploaded/products');
            $file->move($destinationPath, $filename);

            // Save filename to database
            $validated['image'] = $filename;
        }

        $validated['price_p'] = $validated['price_s'] - $validated['price_b'];
        $product->update($validated);

        // Redirect to index instead of back
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && File::exists(public_path('uploaded/products/' . $product->image))) {
            File::delete(public_path('uploaded/products/' . $product->image));
        }

        $product->delete();

        // Redirect to index after deletion
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }
}