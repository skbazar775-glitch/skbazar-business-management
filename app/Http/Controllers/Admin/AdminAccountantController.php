<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accountant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminAccountantController extends Controller
{
    public function index()
    {
        $accountants = Accountant::latest()->paginate(10);
        return view('admin.accountant.index', compact('accountants'));
    }

    public function create()
    {
        return view('admin.accountant.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:accountants,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        Accountant::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.accountants.index')->with('success', 'Accountant created successfully.');
    }

    public function show(Accountant $accountant)
    {
        return view('admin.accountant.show', compact('accountant'));
    }

    public function edit(Accountant $accountant)
    {
        return view('admin.accountant.edit', compact('accountant'));
    }

    public function update(Request $request, Accountant $accountant)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:accountants,email,' . $accountant->id],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $accountant->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? Hash::make($validated['password']) : $accountant->password,
        ]);

        return redirect()->route('admin.accountants.index')->with('success', 'Accountant updated successfully.');
    }

    public function destroy(Accountant $accountant)
    {
        $accountant->delete();
        return redirect()->route('admin.accountants.index')->with('success', 'Accountant deleted successfully.');
    }
}