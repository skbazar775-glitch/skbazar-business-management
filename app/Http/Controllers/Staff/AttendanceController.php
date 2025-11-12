<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Staff;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $attendance = AttendanceRecord::where('staff_id', auth('staff')->id())
            ->where('attendance_date', $today)
            ->first();

        return view('staff.attendance.index', compact('attendance'));
    }

public function store(Request $request)
{
    $today = Carbon::today();
    $attendance = AttendanceRecord::where('staff_id', auth('staff')->id())
        ->where('attendance_date', $today)
        ->first();

    if (!$attendance) {
        $attendance = new AttendanceRecord();
        $attendance->staff_id = auth('staff')->id();
        $attendance->attendance_date = $today;
    }

    $request->validate([
        'type' => 'required|in:first_half_in,first_half_out,second_half_in,second_half_out',
    ]);

    if ($request->type === 'first_half_in' && !$attendance->first_half_in) {
        $attendance->first_half_in = Carbon::now();
        $status = AttendanceRecord::calculateAttendanceStatus($attendance->first_half_in, null, 'first');
        $attendance->first_half_status = $status['status'];
        $attendance->first_half_late_by = $status['late_by'];
    }

    if ($request->type === 'first_half_out' && $attendance->first_half_in && !$attendance->first_half_out) {
        $attendance->first_half_out = Carbon::now();
        $status = AttendanceRecord::calculateAttendanceStatus($attendance->first_half_in, $attendance->first_half_out, 'first');
        $attendance->first_half_status = $status['status'];
        $attendance->first_half_late_by = $status['late_by'];
    }

    if ($request->type === 'second_half_in' && !$attendance->second_half_in) {
        $attendance->second_half_in = Carbon::now();
        $status = AttendanceRecord::calculateAttendanceStatus($attendance->second_half_in, null, 'second');
        $attendance->second_half_status = $status['status'];
        $attendance->second_half_late_by = $status['late_by'];
    }

    if ($request->type === 'second_half_out' && $attendance->second_half_in && !$attendance->second_half_out) {
        $attendance->second_half_out = Carbon::now();
        $status = AttendanceRecord::calculateAttendanceStatus($attendance->second_half_in, $attendance->second_half_out, 'second');
        $attendance->second_half_status = $status['status'];
        $attendance->second_half_late_by = $status['late_by'];
    }

    $attendance->save();

    // ✅ Detailed Log
    Log::info('Attendance action recorded', [
        'staff_id' => auth('staff')->id(),
        'action'   => $request->type,
        'date'     => $today->toDateString(),
        'record'   => $attendance->toArray()
    ]);

    return redirect()->back()->with('success', 'Attendance action recorded successfully');
}


    public function history(Request $request)
    {
        $query = AttendanceRecord::where('staff_id', auth('staff')->id());

        // Filter by month and year
        if ($request->month && $request->year) {
            $query->whereYear('attendance_date', $request->year)
                  ->whereMonth('attendance_date', $request->month);
        } elseif ($request->year) {
            $query->whereYear('attendance_date', $request->year);
        }

        $attendances = $query->orderBy('attendance_date', 'desc')->paginate(10);

        // Generate year and month options for filter
        $years = AttendanceRecord::where('staff_id', auth('staff')->id())
            ->selectRaw('YEAR(attendance_date) as year')
            ->distinct()
            ->pluck('year')
            ->sort()
            ->values();

        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        return view('staff.attendance.history', compact('attendances', 'years', 'months'));
    }



   /**
     * Show all staffs for admin to select and mark attendance
     */
    // ✅ Admin: Show staff list and attendance marking
    public function adminIndex(Request $request)
    {
        // ✅ Fetch all staff
        $staffs = Staff::all();
        // ✅ Get today's date
        $today = Carbon::today();

        // ✅ Fetch today's attendance for all staff
        $attendances = AttendanceRecord::where('attendance_date', $today)
            ->get()
            ->keyBy('staff_id');

        return view('staff.adminmarkattendance.index', compact('staffs', 'attendances'));
    }

    /**
     * Show attendance mark page for a specific staff
     */
public function adminMark($staffId)
    {
        // ✅ Check staff exists
        $staff = Staff::findOrFail($staffId);

        // ✅ Today ka attendance fetch ya blank
        $today = Carbon::today();
        $attendance = AttendanceRecord::where('staff_id', $staff->id)
            ->where('attendance_date', $today)
            ->first();

        return view('staff.adminmarkattendance.mark', compact('staff', 'attendance'));
    }

    /**
     * Store attendance for a specific staff by admin
     */
/**
 * Store attendance for a specific staff by admin
 */
public function adminStore(Request $request, $staffId)
{
    // ✅ Validate staff exists
    $staff = Staff::findOrFail($staffId);
    
    // ✅ Get today's date
    $today = Carbon::today();
    
    // ✅ Fetch or create attendance record
    $attendance = AttendanceRecord::where('staff_id', $staffId)
        ->where('attendance_date', $today)
        ->first();
    
    if (!$attendance) {
        $attendance = new AttendanceRecord();
        $attendance->staff_id = $staffId;
        $attendance->attendance_date = $today;
    }
    
    // ✅ Validate request
    $request->validate([
        'type' => 'required|in:first_half_in,first_half_out,second_half_in,second_half_out',
    ]);
    
    // ✅ Handle attendance types
    if ($request->type === 'first_half_in' && !$attendance->first_half_in) {
        $attendance->first_half_in = Carbon::now();
        $status = AttendanceRecord::calculateAttendanceStatus($attendance->first_half_in, null, 'first');
        $attendance->first_half_status = $status['status'];
        $attendance->first_half_late_by = $status['late_by'];
    }
    
    if ($request->type === 'first_half_out' && $attendance->first_half_in && !$attendance->first_half_out) {
        $attendance->first_half_out = Carbon::now();
        $status = AttendanceRecord::calculateAttendanceStatus($attendance->first_half_in, $attendance->first_half_out, 'first');
        $attendance->first_half_status = $status['status'];
        $attendance->first_half_late_by = $status['late_by'];
    }
    
    if ($request->type === 'second_half_in' && !$attendance->second_half_in) {
        $attendance->second_half_in = Carbon::now();
        $status = AttendanceRecord::calculateAttendanceStatus($attendance->second_half_in, null, 'second');
        $attendance->second_half_status = $status['status'];
        $attendance->second_half_late_by = $status['late_by'];
    }
    
    if ($request->type === 'second_half_out' && $attendance->second_half_in && !$attendance->second_half_out) {
        $attendance->second_half_out = Carbon::now();
        $status = AttendanceRecord::calculateAttendanceStatus($attendance->second_half_in, $attendance->second_half_out, 'second');
        $attendance->second_half_status = $status['status'];
        $attendance->second_half_late_by = $status['late_by'];
    }
    
    // ✅ Save the record
    $attendance->save();
    
    // ✅ Log the action
    Log::info('Admin marked attendance', [
        'staff_id' => $staffId,
        'action' => $request->type,
        'date' => $today->toDateString(),
        'record' => $attendance->toArray(),
    ]);
    
    // ✅ Redirect back to staff list with success message
    return redirect()->route('admin.attendance.index')
        ->with('success', 'Attendance marked successfully for ' . $staff->name);
}



 public function markAttendance(Request $request, $staffId = null)
    {
        // ✅ Determine staff ID based on role
        $staffId = $staffId ?? auth('staff')->id();
        // ✅ Validate staff exists
        $staff = Staff::findOrFail($staffId);
        // ✅ Get today's date
        $today = Carbon::today();

        // ✅ Fetch or create attendance record
        $attendance = AttendanceRecord::where('staff_id', $staffId)
            ->where('attendance_date', $today)
            ->first() ?? new AttendanceRecord(['staff_id' => $staffId, 'attendance_date' => $today]);

        // ✅ Validate request
        $request->validate([
            'type' => 'required|in:first_half_in,first_half_out,second_half_in,second_half_out',
        ]);

        // ✅ Handle attendance types
        $type = $request->type;
        if ($type === 'first_half_in' && !$attendance->first_half_in) {
            $attendance->first_half_in = Carbon::now();
            $status = AttendanceRecord::calculateAttendanceStatus($attendance->first_half_in, null, 'first');
            $attendance->first_half_status = $status['status'];
            $attendance->first_half_late_by = $status['late_by'];
        } elseif ($type === 'first_half_out' && $attendance->first_half_in && !$attendance->first_half_out) {
            $attendance->first_half_out = Carbon::now();
            $status = AttendanceRecord::calculateAttendanceStatus($attendance->first_half_in, $attendance->first_half_out, 'first');
            $attendance->first_half_status = $status['status'];
            $attendance->first_half_late_by = $status['late_by'];
        } elseif ($type === 'second_half_in' && !$attendance->second_half_in) {
            $attendance->second_half_in = Carbon::now();
            $status = AttendanceRecord::calculateAttendanceStatus($attendance->second_half_in, null, 'second');
            $attendance->second_half_status = $status['status'];
            $attendance->second_half_late_by = $status['late_by'];
        } elseif ($type === 'second_half_out' && $attendance->second_half_in && !$attendance->second_half_out) {
            $attendance->second_half_out = Carbon::now();
            $status = AttendanceRecord::calculateAttendanceStatus($attendance->second_half_in, $attendance->second_half_out, 'second');
            $attendance->second_half_status = $status['status'];
            $attendance->second_half_late_by = $status['late_by'];
        }

        // ✅ Save record
        $attendance->save();

        // ✅ Log action
        Log::info('Attendance marked', [
            'staff_id' => $staffId,
            'action' => $type,
            'date' => $today->toDateString(),
            'record' => $attendance->toArray(),
        ]);

        // ✅ Redirect with success
        return redirect()->back()->with('success', 'Attendance marked for ' . $staff->name);
    }


}