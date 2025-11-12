<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:Residential,Commercial,Industrial',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'completed_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.projects.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
            if ($request->hasFile('image_path')) {
    $file = $request->file('image_path');

    // Set destination path: public/uploaded/project_images
    $destinationPath = public_path('uploaded/project_images');

    // Create folder if not exists
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    // Get original filename (or use custom name)
    $filename = time() . '_' . $file->getClientOriginalName();

    // Move file to public folder
    $file->move($destinationPath, $filename);

    // Save relative path in DB
    $data['image_path'] = 'project_images/' . $filename;
}


            Project::create($data);
            return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating project: ' . $e->getMessage());
            return redirect()->route('admin.projects.create')
                ->with('error', 'Failed to create project.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|in:Residential,Commercial,Industrial',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'completed_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.projects.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
                    if ($request->hasFile('image_path')) {
    $file = $request->file('image_path');

    // Set destination path: public/uploaded/project_images
    $destinationPath = public_path('uploaded/project_images');

    // Create folder if not exists
    if (!file_exists($destinationPath)) {
        mkdir($destinationPath, 0755, true);
    }

    // Get original filename (or use custom name)
    $filename = time() . '_' . $file->getClientOriginalName();

    // Move file to public folder
    $file->move($destinationPath, $filename);

    // Save relative path in DB
    $data['image_path'] = 'project_images/' . $filename;
}

            $project->update($data);
            return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating project: ' . $e->getMessage());
            return redirect()->route('admin.projects.edit', $id)
                ->with('error', 'Failed to update project.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id);
            $project->delete();
            return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting project: ' . $e->getMessage());
            return redirect()->route('admin.projects.index')->with('error', 'Failed to delete project.');
        }
    }
}