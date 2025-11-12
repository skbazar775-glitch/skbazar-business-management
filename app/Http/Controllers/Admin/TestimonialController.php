<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::all();
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'message' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.testimonials.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            if ($request->hasFile('image_path')) {
    $file = $request->file('image_path');

    $filename = time() . '_' . $file->getClientOriginalName();

    $file->move(public_path('uploaded/testimonial_images'), $filename);

    $data['image_path'] = 'testimonial_images/' . $filename;
}




            

            Testimonial::create($data);
            return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating testimonial: ' . $e->getMessage());
            return redirect()->route('admin.testimonials.create')
                ->with('error', 'Failed to create testimonial.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'message' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.testimonials.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            if ($request->hasFile('image_path')) {
    $file = $request->file('image_path');

    $filename = time() . '_' . $file->getClientOriginalName();

    $file->move(public_path('uploaded/testimonial_images'), $filename);

    $data['image_path'] = 'testimonial_images/' . $filename;
}

            $testimonial->update($data);
            return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating testimonial: ' . $e->getMessage());
            return redirect()->route('admin.testimonials.edit', $id)
                ->with('error', 'Failed to update testimonial.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $testimonial = Testimonial::findOrFail($id);
            $testimonial->delete();
            return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting testimonial: ' . $e->getMessage());
            return redirect()->route('admin.testimonials.index')->with('error', 'Failed to delete testimonial.');
        }
    }
}