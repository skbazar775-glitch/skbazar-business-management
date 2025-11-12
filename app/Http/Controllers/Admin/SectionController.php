<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::all();
        return view('admin.sections.index', compact('sections'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'is_visible' => 'required|boolean',
        ]);

        $section->update(['is_visible' => $request->is_visible]);

        return redirect()->route('admin.sections.index')->with('success', 'Section updated successfully.');
    }
}