<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ContactRequestController extends Controller
{
    public function index()
    {
        $contactRequests = ContactRequest::all();
        return view('admin.contact-requests.index', compact('contactRequests'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'service' => 'required|string|max:255',
            'message' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('home', ['#contact'])
                ->withErrors($validator)
                ->withInput();
        }

        try {
            ContactRequest::create($request->all());
            return redirect()->route('home', ['#contact'])
                ->with('success', 'Your request has been submitted successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating contact request: ' . $e->getMessage());
            return redirect()->route('home', ['#contact'])
                ->with('error', 'Failed to submit your request. Please try again.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $contactRequest = ContactRequest::findOrFail($id);
            $contactRequest->delete();
            return redirect()->route('admin.contact-requests.index')
                ->with('success', 'Contact request deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting contact request: ' . $e->getMessage());
            return redirect()->route('admin.contact-requests.index')
                ->with('error', 'Failed to delete contact request.');
        }
    }
}