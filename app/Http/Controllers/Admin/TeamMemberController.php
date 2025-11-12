<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TeamMemberController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::all();
        return view('admin.team.index', compact('teamMembers'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'bio' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'linkedin_url' => 'nullable|url',
            'email' => 'nullable|email',
            'color_class' => 'nullable|string|in:text-blue-400,text-green-400,text-yellow-400,text-purple-400',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.team.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
           if ($request->hasFile('image_path')) {
    $file = $request->file('image_path');

    $filename = time() . '_' . $file->getClientOriginalName();

    $file->move(public_path('uploaded/team_images'), $filename);

    $data['image_path'] = 'team_images/' . $filename;
}


            TeamMember::create($data);
            return redirect()->route('admin.team.index')->with('success', 'Team member created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating team member: ' . $e->getMessage());
            return redirect()->route('admin.team.create')
                ->with('error', 'Failed to create team member.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $teamMember = TeamMember::findOrFail($id);
        return view('admin.team.edit', compact('teamMember'));
    }

    public function update(Request $request, $id)
    {
        $teamMember = TeamMember::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'bio' => 'required|string',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'linkedin_url' => 'nullable|url',
            'email' => 'nullable|email',
            'color_class' => 'nullable|string|in:text-blue-400,text-green-400,text-yellow-400,text-purple-400',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.team.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();
                   if ($request->hasFile('image_path')) {
    $file = $request->file('image_path');

    $filename = time() . '_' . $file->getClientOriginalName();

    $file->move(public_path('uploaded/team_images'), $filename);

    $data['image_path'] = 'team_images/' . $filename;
}

            $teamMember->update($data);
            return redirect()->route('admin.team.index')->with('success', 'Team member updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating team member: ' . $e->getMessage());
            return redirect()->route('admin.team.edit', $id)
                ->with('error', 'Failed to update team member.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $teamMember = TeamMember::findOrFail($id);
            $teamMember->delete();
            return redirect()->route('admin.team.index')->with('success', 'Team member deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting team member: ' . $e->getMessage());
            return redirect()->route('admin.team.index')->with('error', 'Failed to delete team member.');
        }
    }
}