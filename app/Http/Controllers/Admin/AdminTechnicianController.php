<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminTechnicianController extends Controller
{
    public function index()
    {
        $technicians = Technician::latest()->paginate(10);
        return view('admin.technician.index', compact('technicians'));
    }

    public function create()
    {
        return view('admin.technician.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:technicians,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Technician::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.technicians.index')->with('success', 'Technician created successfully.');
    }

    public function show(Technician $technician)
    {
        return view('admin.technician.show', compact('technician'));
    }

    public function edit(Technician $technician)
    {
        return view('admin.technician.edit', compact('technician'));
    }

    public function update(Request $request, Technician $technician)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:technicians,email,' . $technician->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $technician->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $technician->password,
        ]);

        return redirect()->route('admin.technicians.index')->with('success', 'Technician updated successfully.');
    }

    public function destroy(Technician $technician)
    {
        $technician->delete();
        return redirect()->route('admin.technicians.index')->with('success', 'Technician deleted successfully.');
    }
}