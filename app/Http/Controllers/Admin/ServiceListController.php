<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceList;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceListController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $category_id = $request->query('category_id');
        
        $services = ServiceList::when($category_id, function ($query, $category_id) {
            return $query->where('category_id', $category_id);
        })->latest()->paginate(10);

        return view('admin.servicelist.index', compact('services', 'categories', 'category_id'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.servicelist.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
        ]);

        ServiceList::create($validated);

        return redirect()->route('admin.servicelist.index')
            ->with('success', 'Service created successfully.');
    }

    public function edit(ServiceList $service)
    {
        $categories = Category::all();
        return view('admin.servicelist.edit', compact('service', 'categories'));
    }

    public function update(Request $request, ServiceList $service)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:0,1',
        ]);

        $service->update($validated);

        return redirect()->route('admin.servicelist.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(ServiceList $service)
    {
        $service->delete();
        return redirect()->route('admin.servicelist.index')
            ->with('success', 'Service deleted successfully.');
    }
}