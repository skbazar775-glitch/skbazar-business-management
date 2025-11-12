<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookedService;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookedServicesController extends Controller
{
    /**
     * Display the list of booked services.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Fetch all booked services with relationships
        $bookings = BookedService::with([
            'service:id,name,price,category_id',
            'service.category:id,title',
            'user:id,name',
            'address:id,area,phone,name,city,district,pin_code',
            'staff:id,name'
        ])
        ->latest()
        ->get();

        // Fetch all staff members for the dropdown
        $staffMembers = Staff::select('id', 'name')->get();

        return view('admin.bookedservices.index', compact('bookings', 'staffMembers'));
    }

    /**
     * Update the status of a booked service.
     *
     * @param Request $request
     * @param BookedService $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, BookedService $booking)
    {
        try {
            // Log request data for debugging
            Log::info('Update Status Request:', [
                'booking_id' => $booking->id,
                'request_data' => $request->all()
            ]);

            // Validate the request
            $validated = $request->validate([
                'status' => 'required|integer|in:0,1,2,3,4,5',
            ]);

            // Update the booking status
            $booking->update(['status' => $validated['status']]);

            return redirect()->route('admin.bookedservices.index')
                ->with('success', 'Booking status updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return redirect()->route('admin.bookedservices.index')
                ->with('error', 'Invalid status value: ' . implode(', ', $e->errors()['status']));
        } catch (\Exception $e) {
            Log::error('Update Status Error:', ['error' => $e->getMessage()]);
            return redirect()->route('admin.bookedservices.index')
                ->with('error', 'An error occurred while updating the booking status: ' . $e->getMessage());
        }
    }

    /**
     * Assign a staff member to a booked service.
     *
     * @param Request $request
     * @param BookedService $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignStaff(Request $request, BookedService $booking)
    {
        try {
            // Log request data for debugging
            Log::info('Assign Staff Request:', [
                'booking_id' => $booking->id,
                'request_data' => $request->all()
            ]);

            // Validate the request
            $validated = $request->validate([
                'staff_id' => 'required|exists:staff,id',
            ]);

            // Update the booking with staff_id and set status to 'Staff Assigned' (2)
            $booking->update([
                'staff_id' => $validated['staff_id'],
                'status' => 2 // Staff Assigned status
            ]);

            return redirect()->route('admin.bookedservices.index')
                ->with('success', 'Staff assigned successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return redirect()->route('admin.bookedservices.index')
                ->with('error', 'Invalid staff selection: ' . implode(', ', $e->errors()['staff_id']));
        } catch (\Exception $e) {
            Log::error('Assign Staff Error:', ['error' => $e->getMessage()]);
            return redirect()->route('admin.bookedservices.index')
                ->with('error', 'An error occurred while assigning staff: ' . $e->getMessage());
        }
    }
}