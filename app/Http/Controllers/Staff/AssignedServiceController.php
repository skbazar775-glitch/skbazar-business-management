<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\BookedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AssignedServiceController extends Controller
{
    /**
     * Display the list of services assigned to the authenticated staff.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Fetch services assigned to the authenticated staff
        $bookings = BookedService::with([
            'service:id,name,price,category_id',
            'service.category:id,title',
            'user:id,name',
            'address:id,area,phone,name,city,district,pin_code'
        ])
        ->where('staff_id', Auth::guard('staff')->id())
        ->latest()
        ->get();

        return view('staff.assignedservice.index', compact('bookings'));
    }

    /**
     * Update the status of an assigned service.
     *
     * @param Request $request
     * @param BookedService $booking
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, BookedService $booking)
    {
        try {
            // Ensure the booking is assigned to the authenticated staff
            if ($booking->staff_id !== Auth::guard('staff')->id()) {
                return redirect()->route('staff.assignedservice.index')
                    ->with('error', 'You are not authorized to update this booking.');
            }

            // Log request data for debugging
            Log::info('Update Status Request:', [
                'booking_id' => $booking->id,
                'staff_id' => Auth::guard('staff')->id(),
                'request_data' => $request->all()
            ]);

            // Validate the request
            $validated = $request->validate([
                'status' => 'required|integer|in:2,3,4,5', // Staff can only update to Staff Assigned, Running Work, Work Done, or Canceled
            ]);

            // Update the booking status
            $booking->update(['status' => $validated['status']]);

            return redirect()->route('staff.assignedservice.index')
                ->with('success', 'Booking status updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return redirect()->route('staff.assignedservice.index')
                ->with('error', 'Invalid status value: ' . implode(', ', $e->errors()['status']));
        } catch (\Exception $e) {
            Log::error('Update Status Error:', ['error' => $e->getMessage()]);
            return redirect()->route('staff.assignedservice.index')
                ->with('error', 'An error occurred while updating the booking status: ' . $e->getMessage());
        }
    }
}