<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolarSolution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SolarSolutionController extends Controller
{
    public function index()
    {
        $solarSolutions = SolarSolution::all();
        return view('admin.solutions.index', compact('solarSolutions'));
    }

    public function create()
    {
        return view('admin.solutions.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'color_class' => 'nullable|string|in:hover-glow,hover-glow-green,hover-glow-yellow',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.solutions.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            if ($request->hasFile('image_path')) {
    $file = $request->file('image_path');

    $filename = time() . '_' . $file->getClientOriginalName();

    $file->move(public_path('uploaded/solution_images'), $filename);

    $data['image_path'] = 'solution_images/' . $filename;
}




            SolarSolution::create($data);
            return redirect()->route('admin.solutions.index')->with('success', 'Solar solution created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating solar solution: ' . $e->getMessage());
            return redirect()->route('admin.solutions.create')
                ->with('error', 'Failed to create solar solution.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $solarSolution = SolarSolution::findOrFail($id);
        return view('admin.solutions.edit', compact('solarSolution'));
    }

    public function update(Request $request, $id)
    {
        $solarSolution = SolarSolution::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'color_class' => 'nullable|string|in:hover-glow,hover-glow-green,hover-glow-yellow',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.solutions.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
                 if ($request->hasFile('image_path')) {
    $file = $request->file('image_path');

    $filename = time() . '_' . $file->getClientOriginalName();

    $file->move(public_path('uploaded/solution_images'), $filename);

    $data['image_path'] = 'solution_images/' . $filename;
}

            $solarSolution->update($data);
            return redirect()->route('admin.solutions.index')->with('success', 'Solar solution updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating solar solution: ' . $e->getMessage());
            return redirect()->route('admin.solutions.edit', $id)
                ->with('error', 'Failed to update solar solution.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $solarSolution = SolarSolution::findOrFail($id);
            $solarSolution->delete();
            return redirect()->route('admin.solutions.index')->with('success', 'Solar solution deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting solar solution: ' . $e->getMessage());
            return redirect()->route('admin.solutions.index')->with('error', 'Failed to delete solar solution.');
        }
    }
}