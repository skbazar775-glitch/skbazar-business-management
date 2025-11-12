<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactInfoController extends Controller
{
    public function index()
    {
        $contactInfo = ContactInfo::first();
        return view('admin.contact-info.index', compact('contactInfo'));
    }

    public function create()
    {
        $contactInfo = ContactInfo::first();
        if ($contactInfo) {
            return redirect()->route('admin.contact-info.edit', $contactInfo->id);
        }
        return view('admin.contact-info.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'office_title' => 'required|string|max:255',
            'office_address' => 'required|string',
            'phone_title' => 'required|string|max:255',
            'phone_1' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'email_title' => 'required|string|max:255',
            'email_1' => 'required|email|max:255',
            'email_2' => 'nullable|email|max:255',
            'hours_title' => 'required|string|max:255',
            'weekdays_hours' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.contact-info.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            ContactInfo::create($request->all());
            return redirect()->route('admin.contact-info.index')
                ->with('success', 'Contact information created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating contact information: ' . $e->getMessage());
            return redirect()->route('admin.contact-info.create')
                ->with('error', 'Failed to create contact information.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        $contactInfo = ContactInfo::findOrFail($id);
        return view('admin.contact-info.edit', compact('contactInfo'));
    }

    public function update(Request $request, $id)
    {
        $contactInfo = ContactInfo::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'office_title' => 'required|string|max:255',
            'office_address' => 'required|string',
            'phone_title' => 'required|string|max:255',
            'phone_1' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'email_title' => 'required|string|max:255',
            'email_1' => 'required|email|max:255',
            'email_2' => 'nullable|email|max:255',
            'hours_title' => 'required|string|max:255',
            'weekdays_hours' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.contact-info.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $contactInfo->update($request->all());
            return redirect()->route('admin.contact-info.index')
                ->with('success', 'Contact information updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating contact information: ' . $e->getMessage());
            return redirect()->route('admin.contact-info.edit', $id)
                ->with('error', 'Failed to update contact information.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $contactInfo = ContactInfo::findOrFail($id);
            $contactInfo->delete();
            return redirect()->route('admin.contact-info.index')
                ->with('success', 'Contact information deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting contact information: ' . $e->getMessage());
            return redirect()->route('admin.contact-info.index')
                ->with('error', 'Failed to delete contact information.');
        }
    }
}