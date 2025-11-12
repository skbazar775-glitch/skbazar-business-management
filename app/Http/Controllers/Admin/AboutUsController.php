<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AboutUsController extends Controller
{
    public function index()
    {
        $aboutUs = AboutUs::first();
        return view('admin.about-us.index', compact('aboutUs'));
    }

    public function create()
    {
        $aboutUs = AboutUs::first();
        if ($aboutUs) {
            return redirect()->route('admin.about-us.edit', $aboutUs->id);
        }
        return view('admin.about-us.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'point_1' => 'required|string|max:255',
            'point_2' => 'required|string|max:255',
            'point_3' => 'required|string|max:255',
            'button_text' => 'required|string|max:255',
            'main_image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ceo_image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ceo_name' => 'required|string|max:255',
            'ceo_title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.about-us.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            if ($request->hasFile('main_image_path')) {
    $file = $request->file('main_image_path');

    $filename = time() . '_' . $file->getClientOriginalName();

    $file->move(public_path('uploaded/about_us_images'), $filename);

    $data['main_image_path'] = 'about_us_images/' . $filename;
}




if ($request->hasFile('ceo_image_path')) {
    $file = $request->file('ceo_image_path');

    $filename = time() . '_' . $file->getClientOriginalName();

    $file->move(public_path('uploaded/about_us_images'), $filename);

    $data['ceo_image_path'] = 'about_us_images/' . $filename;
}

            
            
            
            
         
            AboutUs::create($data);
            return redirect()->route('admin.about-us.index')->with('success', 'About Us content created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating About Us content: ' . $e->getMessage());
            return redirect()->route('admin.about-us.create')
                ->with('error', 'Failed to create About Us content.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $aboutUs = AboutUs::findOrFail($id);
        return view('admin.about-us.edit', compact('aboutUs'));
    }

    public function update(Request $request, $id)
    {
        $aboutUs = AboutUs::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'point_1' => 'required|string|max:255',
            'point_2' => 'required|string|max:255',
            'point_3' => 'required|string|max:255',
            'button_text' => 'required|string|max:255',
            'main_image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ceo_image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ceo_name' => 'required|string|max:255',
            'ceo_title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.about-us.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            if ($request->hasFile('main_image_path')) {
    $file = $request->file('main_image_path');

    $filename = time() . '_' . $file->getClientOriginalName();

    $file->move(public_path('uploaded/about_us_images'), $filename);

    $data['main_image_path'] = 'about_us_images/' . $filename;
}




if ($request->hasFile('ceo_image_path')) {
    $file = $request->file('ceo_image_path');

    $filename = time() . '_' . $file->getClientOriginalName();

    $file->move(public_path('uploaded/about_us_images'), $filename);

    $data['ceo_image_path'] = 'about_us_images/' . $filename;
}

            $aboutUs->update($data);
            return redirect()->route('admin.about-us.index')->with('success', 'About Us content updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating About Us content: ' . $e->getMessage());
            return redirect()->route('admin.about-us.edit', $id)
                ->with('error', 'Failed to update About Us content.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $aboutUs = AboutUs::findOrFail($id);
            $aboutUs->delete();
            return redirect()->route('admin.about-us.index')->with('success', 'About Us content deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting About Us content: ' . $e->getMessage());
            return redirect()->route('admin.about-us.index')->with('error', 'Failed to delete About Us content.');
        }
    }
}