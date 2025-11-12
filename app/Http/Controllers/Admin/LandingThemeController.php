<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\HeroSection;
use App\Models\Project;
use App\Models\TeamMember;
use App\Models\SolarSolution;
use App\Models\Testimonial;
use App\Models\AboutUs;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LandingThemeController extends Controller
{
    public function index(Request $request)
    {
        try {
            $sections = Section::all()->keyBy('name');
            $heroSection = HeroSection::first();
            $projects = Project::all();
            $teamMembers = TeamMember::all();
            $solarSolutions = SolarSolution::all();
            $testimonials = Testimonial::all();
            $aboutUs = AboutUs::first();
            $contactInfo = ContactInfo::first();

            return view('welcome', [
                'sections' => $sections,
                'heroSection' => $heroSection,
                'projects' => $projects,
                'teamMembers' => $teamMembers,
                'solarSolutions' => $solarSolutions,
                'testimonials' => $testimonials,
                'aboutUs' => $aboutUs,
                'contactInfo' => $contactInfo,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching data for welcome page: ' . $e->getMessage());
            return view('welcome', [
                'sections' => collect([]),
                'heroSection' => null,
                'projects' => collect([]),
                'teamMembers' => collect([]),
                'solarSolutions' => collect([]),
                'testimonials' => collect([]),
                'aboutUs' => null,
                'contactInfo' => null,
            ])->with('error', 'Failed to load page data. Please try again later.');
        }
    }

    public function adminIndex()
    {
        $sections = Section::all();
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        return view('admin.sections.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:sections',
            'is_visible' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.sections.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Section::create([
                'name' => $request->name,
                'is_visible' => $request->is_visible,
            ]);

            return redirect()->route('admin.sections.index')
                ->with('success', 'Section created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating section: ' . $e->getMessage());
            return redirect()->route('admin.sections.create')
                ->with('error', 'Failed to create section. Please try again.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $section = Section::findOrFail($id);
        return view('admin.sections.edit', compact('section'));
    }

    public function update(Request $request, $id)
    {
        $section = Section::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:sections,name,' . $id,
            'is_visible' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.sections.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $section->update([
                'name' => $request->name,
                'is_visible' => $request->is_visible,
            ]);

            return redirect()->route('admin.sections.index')
                ->with('success', 'Section updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating section: ' . $e->getMessage());
            return redirect()->route('admin.sections.edit', $id)
                ->with('error', 'Failed to update section. Please try again.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $section = Section::findOrFail($id);
            $section->delete();

            return redirect()->route('admin.sections.index')
                ->with('success', 'Section deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting section: ' . $e->getMessage());
            return redirect()->route('admin.sections.index')
                ->with('error', 'Failed to delete section. Please try again.');
        }
    }
}