<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class HeroSectionController extends Controller
{
    public function index()
    {
        $heroSections = HeroSection::all();
        return view('admin.hero.index', compact('heroSections'));
    }

    public function create()
    {
        return view('admin.hero.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'heading' => 'nullable|string|max:255',
            'highlighted_text' => 'nullable|string|max:255',
            'subtext' => 'nullable|string',
            'button1_text' => 'nullable|string|max:100',
            'button1_link' => 'nullable|url',
            'button2_text' => 'nullable|string|max:100',
            'button2_link' => 'nullable|url',
            'icon_svg' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'scroll_target' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.hero.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();

           if ($request->hasFile('image_path')) {
    $file = $request->file('image_path');

    $filename = time() . '_' . $file->getClientOriginalName();

    // Move to custom folder: public/uploaded/hero_images
    $file->move(public_path('uploaded/hero_images'), $filename);

    // Save path in DB if needed
    $data['image_path'] = 'uploaded/hero_images/' . $filename;
}


            HeroSection::create($data);
            return redirect()->route('admin.hero.index')->with('success', 'Hero section created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating hero section: ' . $e->getMessage());
            return redirect()->route('admin.hero.create')
                ->with('error', 'Failed to create hero section.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $heroSection = HeroSection::findOrFail($id);
        return view('admin.hero.edit', compact('heroSection'));
    }

    public function update(Request $request, $id)
    {
        $heroSection = HeroSection::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'heading' => 'nullable|string|max:255',
            'highlighted_text' => 'nullable|string|max:255',
            'subtext' => 'nullable|string',
            'button1_text' => 'nullable|string|max:100',
            'button1_link' => 'nullable|url',
            'button2_text' => 'nullable|string|max:100',
            'button2_link' => 'nullable|url',
            'icon_svg' => 'nullable|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'scroll_target' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.hero.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();

            if ($request->hasFile('image_path')) {
    $file = $request->file('image_path');

    $filename = time() . '_' . $file->getClientOriginalName();

    // Move to custom folder: public/uploaded/hero_images
    $file->move(public_path('uploaded/hero_images'), $filename);

    // Save path in DB if needed
    $data['image_path'] = 'hero_images/' . $filename;
}

            $heroSection->update($data);
            return redirect()->route('admin.hero.index')->with('success', 'Hero section updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating hero section: ' . $e->getMessage());
            return redirect()->route('admin.hero.edit', $id)
                ->with('error', 'Failed to update hero section.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $heroSection = HeroSection::findOrFail($id);
            $heroSection->delete();
            return redirect()->route('admin.hero.index')->with('success', 'Hero section deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting hero section: ' . $e->getMessage());
            return redirect()->route('admin.hero.index')->with('error', 'Failed to delete hero section.');
        }
    }
}
