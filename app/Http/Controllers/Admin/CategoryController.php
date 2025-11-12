<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        
        if ($request->has('q')) {
            $query->where('title', 'like', '%' . $request->q . '%');
        }

        $categories = $query->latest()->paginate(10);

        return view('admin.inventory.category.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.inventory.category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'slug' => 'nullable|unique:categories,slug',
        ]);

        $category = new Category();
        $category->title = $validated['title'];
        $category->description = $validated['description'];
        $category->slug = $validated['slug'] ?? Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '-' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('category'), $filename);
            $category->image = $filename;
        }

        $category->save();

        // Redirect to index instead of back
        return redirect()->route('admin.category.index')->with('success', 'Category created successfully');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.inventory.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'image' => 'nullable|image|max:2048',
            'slug' => 'nullable|unique:categories,slug,' . $id,
        ]);

        $category->title = $validated['title'];
        $category->description = $validated['description'];
        $category->slug = $validated['slug'] ?? Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image && File::exists(public_path('category/' . $category->image))) {
                File::delete(public_path('category/' . $category->image));
            }

            $file = $request->file('image');
            $filename = time() . '-' . Str::slug($validated['title']) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('category'), $filename);
            $category->image = $filename;
        }

        $category->save();

        // Redirect to index instead of back
        return redirect()->route('admin.category.index')->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image && File::exists(public_path('category/' . $category->image))) {
            File::delete(public_path('category/' . $category->image));
        }

        $category->delete();

        // Redirect to index instead of back
        return redirect()->route('admin.category.index')->with('success', 'Category deleted successfully');
    }
}