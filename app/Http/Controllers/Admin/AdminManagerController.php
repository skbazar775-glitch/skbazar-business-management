<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminManagerController extends Controller
{
    public function index()
    {
        $managers = Manager::latest()->paginate(10);
        return view('admin.manager.index', compact('managers'));
    }

    public function create()
    {
        return view('admin.manager.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:managers,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Manager::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.managers.index')->with('success', 'Manager created successfully.');
    }

    public function show(Manager $manager)
    {
        return view('admin.manager.show', compact('manager'));
    }

    public function edit(Manager $manager)
    {
        return view('admin.manager.edit', compact('manager'));
    }

    public function update(Request $request, Manager $manager)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:managers,email,' . $manager->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $manager->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $manager->password,
        ]);

        return redirect()->route('admin.managers.index')->with('success', 'Manager updated successfully.');
    }

public function destroy(Manager $manager, Request $request)
{
    $manager->delete();

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Manager deleted successfully.'
        ]);
    }

    return redirect()->route('admin.managers.index')->with('success', 'Manager deleted successfully.');
}

}