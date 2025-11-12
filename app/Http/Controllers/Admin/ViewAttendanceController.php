<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\DailyBonus;
use App\Models\SalaryRecord;
use App\Models\Staff;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewAttendanceController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = AttendanceRecord::with('staff');

            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->whereHas('staff', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            }

            $attendanceRecords = $query->paginate(10)->appends(['search' => $request->search]);

            $attendanceRecords->getCollection()->transform(function ($record) {
                Log::info('Processing record ID: ' . $record->id);

                $firstHalfHours = 0;
                $secondHalfHours = 0;

                try {
                    if ($record->first_half_in && $record->first_half_out) {
                        $in = Carbon::parse($record->first_half_in);
                        $out = Carbon::parse($record->first_half_out);
                        if ($out->greaterThanOrEqualTo($in)) {
                            $firstHalfHours = $out->diffInHours($in, true);
                        } else {
                            Log::warning('Invalid first half times for record ID ' . $record->id, [
                                'first_half_in' => $record->first_half_in,
                                'first_half_out' => $record->first_half_out,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error calculating first half hours for record ID ' . $record->id, [
                        'error' => $e->getMessage(),
                        'first_half_in' => $record->first_half_in,
                        'first_half_out' => $record->first_half_out,
                    ]);
                }

                try {
                    if ($record->second_half_in && $record->second_half_out) {
                        $in = Carbon::parse($record->second_half_in);
                        $out = Carbon::parse($record->second_half_out);
                        if ($out->greaterThanOrEqualTo($in)) {
                            $secondHalfHours = $out->diffInHours($in, true);
                        } else {
                            Log::warning('Invalid second half times for record ID ' . $record->id, [
                                'second_half_in' => $record->second_half_in,
                                'second_half_out' => $record->second_half_out,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error calculating second half hours for record ID ' . $record->id, [
                        'error' => $e->getMessage(),
                        'second_half_in' => $record->second_half_in,
                        'second_half_out' => $record->second_half_out,
                    ]);
                }

                return [
                    'id' => $record->id,
                    'staff_name' => $record->staff ? $record->staff->name : 'Unknown',
                    'attendance_date' => $record->attendance_date ? Carbon::parse($record->attendance_date)->format('Y-m-d') : 'N/A',
                    'first_half_in' => $record->first_half_in ? Carbon::parse($record->first_half_in)->format('h:i:s A') : null,
                    'first_half_out' => $record->first_half_out ? Carbon::parse($record->first_half_out)->format('h:i:s A') : null,
                    'first_half_status' => $record->first_half_status ?? 'N/A',
                    'first_half_late_by' => $record->first_half_late_by ?? 'N/A',
                    'second_half_in' => $record->second_half_in ? Carbon::parse($record->second_half_in)->format('h:i:s A') : null,
                    'second_half_out' => $record->second_half_out ? Carbon::parse($record->second_half_out)->format('h:i:s A') : null,
                    'second_half_status' => $record->second_half_status ?? 'N/A',
                    'second_half_late_by' => $record->second_half_late_by ?? 'N/A',
                    'remarks' => $record->remarks ?? 'N/A',
                    'first_half_hours' => $firstHalfHours,
                    'second_half_hours' => $secondHalfHours,
                    'total_hours' => ($firstHalfHours + $secondHalfHours),
                ];
            });

            return view('admin.viewattendance.index', compact('attendanceRecords'));
        } catch (\Exception $e) {
            Log::error('Error fetching attendance records', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while fetching attendance records.');
        }
    }

    public function show($id, Request $request)
    {
        try {
            if (!$id) {
                Log::error('No ID provided for show method');
                return redirect()->route('admin.viewattendance.index')->with('error', 'Invalid attendance record ID.');
            }

            $record = AttendanceRecord::with('staff')->findOrFail($id);

            $firstHalfHours = 0;
            $secondHalfHours = 0;

            try {
                if ($record->first_half_in && $record->first_half_out) {
                    $in = Carbon::parse($record->first_half_in);
                    $out = Carbon::parse($record->first_half_out);
                    if ($out->greaterThanOrEqualTo($in)) {
                        $firstHalfHours = $out->diffInHours($in, true);
                    } else {
                        Log::warning('Invalid first half times for record ID ' . $record->id, [
                            'first_half_in' => $record->first_half_in,
                            'first_half_out' => $record->first_half_out,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error calculating first half hours for record ID ' . $record->id, [
                    'error' => $e->getMessage(),
                    'first_half_in' => $record->first_half_in,
                    'first_half_out' => $record->first_half_out,
                ]);
            }

            try {
                if ($record->second_half_in && $record->second_half_out) {
                    $in = Carbon::parse($record->second_half_in);
                    $out = Carbon::parse($record->second_half_out);
                    if ($out->greaterThanOrEqualTo($in)) {
                        $secondHalfHours = $out->diffInHours($in, true);
                    } else {
                        Log::warning('Invalid second half times for record ID ' . $record->id, [
                            'second_half_in' => $record->second_half_in,
                            'second_half_out' => $record->second_half_out,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error calculating second half hours for record ID ' . $record->id, [
                    'error' => $e->getMessage(),
                    'second_half_in' => $record->second_half_in,
                    'second_half_out' => $record->second_half_out,
                ]);
            }

            $attendanceRecord = [
                'id' => $record->id,
                'staff_id' => $record->staff_id,
                'staff_name' => $record->staff ? $record->staff->name : 'Unknown',
                'attendance_date' => $record->attendance_date ? Carbon::parse($record->attendance_date)->format('Y-m-d') : 'N/A',
                'first_half_in' => $record->first_half_in ? Carbon::parse($record->first_half_in)->format('h:i:s A') : null,
                'first_half_out' => $record->first_half_out ? Carbon::parse($record->first_half_out)->format('h:i:s A') : null,
                'first_half_status' => $record->first_half_status ?? 'N/A',
                'first_half_late_by' => $record->first_half_late_by ?? 'N/A',
                'second_half_in' => $record->second_half_in ? Carbon::parse($record->second_half_in)->format('h:i:s A') : null,
                'second_half_out' => $record->second_half_out ? Carbon::parse($record->second_half_out)->format('h:i:s A') : null,
                'second_half_status' => $record->second_half_status ?? 'N/A',
                'second_half_late_by' => $record->second_half_late_by ?? 'N/A',
                'remarks' => $record->remarks ?? 'N/A',
                'first_half_hours' => $firstHalfHours,
                'second_half_hours' => $secondHalfHours,
                'total_hours' => ($firstHalfHours + $secondHalfHours),
            ];

            $monthFilter = $request->input('month_filter', null);
            $monthlyHours = [];

            $query = AttendanceRecord::where('staff_id', $record->staff_id);

            if ($monthFilter) {
                $query->whereYear('attendance_date', Carbon::parse($monthFilter)->year)
                      ->whereMonth('attendance_date', Carbon::parse($monthFilter)->month);
            }

            $records = $query->get();

            $groupedRecords = $records->groupBy(function ($item) {
                return Carbon::parse($item->attendance_date)->format('Y-m');
            });

            foreach ($groupedRecords as $month => $monthRecords) {
                $totalHours = 0;
                foreach ($monthRecords as $rec) {
                    $fhHours = 0;
                    $shHours = 0;

                    if ($rec->first_half_in && $rec->first_half_out) {
                        $in = Carbon::parse($rec->first_half_in);
                        $out = Carbon::parse($rec->first_half_out);
                        if ($out->greaterThanOrEqualTo($in)) {
                            $fhHours = $out->diffInHours($in, true);
                        }
                    }

                    if ($rec->second_half_in && $rec->second_half_out) {
                        $in = Carbon::parse($rec->second_half_in);
                        $out = Carbon::parse($rec->second_half_out);
                        if ($out->greaterThanOrEqualTo($in)) {
                            $shHours = $out->diffInHours($in, true);
                        }
                    }

                    $totalHours += ($fhHours + $shHours);
                }
                $monthlyHours[$month] = $totalHours;
            }

            ksort($monthlyHours);

            return view('admin.viewattendance.show', compact('attendanceRecord', 'monthlyHours', 'monthFilter'));
        } catch (\Exception $e) {
            Log::error('Error fetching attendance record ID ' . $id, ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while fetching the attendance record.');
        }
    }

    public function calculateSalary($staffId, Request $request)
    {
        try {
            $staff = Staff::findOrFail($staffId);
            $monthFilter = $request->input('month_filter', null);
            $monthlyHours = [];
            $dailyRecords = [];

            $query = AttendanceRecord::where('staff_id', $staffId);
            if ($monthFilter) {
                $query->whereYear('attendance_date', Carbon::parse($monthFilter)->year)
                      ->whereMonth('attendance_date', Carbon::parse($monthFilter)->month);
            }
            $records = $query->orderBy('attendance_date', 'asc')->get();

            $bonusQuery = DailyBonus::where('staff_id', $staffId);
            if ($monthFilter) {
                $bonusQuery->whereYear('date', Carbon::parse($monthFilter)->year)
                          ->whereMonth('date', Carbon::parse($monthFilter)->month);
            }
            $bonuses = $bonusQuery->get()->keyBy(function ($bonus) {
                return Carbon::parse($bonus->date)->format('Y-m-d');
            });

            $perShiftSalary = $staff->salary / 2;

            foreach ($records as $rec) {
                $fhHours = 0;
                $shHours = 0;

                try {
                    if ($rec->first_half_in && $rec->first_half_out) {
                        $in = Carbon::parse($rec->first_half_in);
                        $out = Carbon::parse($rec->first_half_out);
                        if ($out->greaterThanOrEqualTo($in)) {
                            $fhHours = $out->diffInHours($in, true);
                        } else {
                            Log::warning('Invalid first half times for record ID ' . $rec->id, [
                                'first_half_in' => $rec->first_half_in,
                                'first_half_out' => $rec->first_half_out,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error calculating first half hours for record ID ' . $rec->id, [
                        'error' => $e->getMessage(),
                        'first_half_in' => $rec->first_half_in,
                        'first_half_out' => $rec->first_half_out,
                    ]);
                }

                try {
                    if ($rec->second_half_in && $rec->second_half_out) {
                        $in = Carbon::parse($rec->second_half_in);
                        $out = Carbon::parse($rec->second_half_out);
                        if ($out->greaterThanOrEqualTo($in)) {
                            $shHours = $out->diffInHours($in, true);
                        } else {
                            Log::warning('Invalid second half times for record ID ' . $rec->id, [
                                'second_half_in' => $rec->second_half_in,
                                'second_half_out' => $rec->second_half_out,
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Error calculating second half hours for record ID ' . $rec->id, [
                        'error' => $e->getMessage(),
                        'second_half_in' => $rec->second_half_in,
                        'second_half_out' => $rec->second_half_out,
                    ]);
                }

                $date = Carbon::parse($rec->attendance_date)->format('Y-m-d');
                $firstHalfAttended = $fhHours > 0;
                $secondHalfAttended = $shHours > 0;
                $dailySalary = 0;
                $hoursWorked = 0;

                if ($firstHalfAttended && $secondHalfAttended) {
                    $dailySalary = $staff->salary;
                    $hoursWorked = $fhHours + $shHours;
                } elseif ($firstHalfAttended) {
                    $dailySalary = $perShiftSalary;
                    $hoursWorked = $fhHours;
                } elseif ($secondHalfAttended) {
                    $dailySalary = $perShiftSalary;
                    $hoursWorked = $shHours;
                }

                $bonus = isset($bonuses[$date]) ? floatval($bonuses[$date]->bonus_amount) : 0;

                SalaryRecord::updateOrCreate(
                    [
                        'staff_id' => $staffId,
                        'date' => $date,
                    ],
                    [
                        'first_half_hours' => $fhHours,
                        'second_half_hours' => $shHours,
                        'total_hours' => $hoursWorked,
                        'daily_salary' => $dailySalary,
                        'bonus_amount' => $bonus,
                    ]
                );

                $dailyRecords[] = [
                    'date' => $date,
                    'first_half_hours' => $fhHours,
                    'second_half_hours' => $shHours,
                    'total_hours' => $hoursWorked,
                    'daily_salary' => $dailySalary,
                    'bonus' => $bonus,
                ];
            }

            $groupedRecords = $records->groupBy(function ($item) {
                return Carbon::parse($item->attendance_date)->format('Y-m');
            });

            foreach ($groupedRecords as $month => $monthRecords) {
                $totalHours = 0;
                foreach ($monthRecords as $rec) {
                    $fhHours = 0;
                    $shHours = 0;

                    if ($rec->first_half_in && $rec->first_half_out) {
                        $in = Carbon::parse($rec->first_half_in);
                        $out = Carbon::parse($rec->first_half_out);
                        if ($out->greaterThanOrEqualTo($in)) {
                            $fhHours = $out->diffInHours($in, true);
                        }
                    }

                    if ($rec->second_half_in && $rec->second_half_out) {
                        $in = Carbon::parse($rec->second_half_in);
                        $out = Carbon::parse($rec->second_half_out);
                        if ($out->greaterThanOrEqualTo($in)) {
                            $shHours = $out->diffInHours($in, true);
                        }
                    }

                    $totalHours += ($fhHours + $shHours);
                }
                $monthlyHours[$month] = $totalHours;
            }

            ksort($monthlyHours);

            return view('admin.viewattendance.calculate_salary', compact('staff', 'monthlyHours', 'monthFilter', 'dailyRecords'));
        } catch (\Exception $e) {
            Log::error('Error fetching salary calculation for staff ID ' . $staffId, ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while fetching salary calculation.');
        }
    }

    public function storeBonuses(Request $request, $staffId)
    {
        try {
            $request->validate([
                'bonuses' => 'required|array',
                'bonuses.*.date' => 'required|date',
                'bonuses.*.amount' => 'required|numeric|min:0',
            ]);

            foreach ($request->bonuses as $bonusData) {
                $date = Carbon::parse($bonusData['date'])->format('Y-m-d');
                $amount = floatval($bonusData['amount']);

                DailyBonus::updateOrCreate(
                    [
                        'staff_id' => $staffId,
                        'date' => $date,
                    ],
                    [
                        'bonus_amount' => $amount,
                    ]
                );

                SalaryRecord::updateOrCreate(
                    [
                        'staff_id' => $staffId,
                        'date' => $date,
                    ],
                    [
                        'bonus_amount' => $amount,
                    ]
                );
            }

            return redirect()->route('admin.viewattendance.calculate_salary', $staffId)
                             ->with('success', 'Bonuses updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error storing bonuses for staff ID ' . $staffId, ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while updating bonuses.');
        }
    }
 public function paySlip($staffId, Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'month_filter' => 'nullable|date_format:Y-m',
            ]);

            // Fetch staff
            $staff = Staff::findOrFail($staffId);

            $monthFilter = $request->input('month_filter', null);
            $monthlyHours = [];
            $dailyRecords = [];

            // Single query for salary records
            $query = SalaryRecord::where('staff_id', $staffId);
            if ($monthFilter) {
                $parsedDate = Carbon::createFromFormat('Y-m', $monthFilter)->startOfMonth();
                $query->whereYear('date', $parsedDate->year)
                      ->whereMonth('date', $parsedDate->month);
            }
            $records = $query->orderBy('date', 'asc')->get();

            // Process daily records
            foreach ($records as $rec) {
                $dailyRecords[] = [
                    'date' => Carbon::parse($rec->date)->format('Y-m-d'),
                    'first_half_hours' => floatval($rec->first_half_hours ?? 0),
                    'second_half_hours' => floatval($rec->second_half_hours ?? 0),
                    'total_hours' => floatval($rec->total_hours ?? 0),
                    'daily_salary' => floatval($rec->daily_salary ?? 0),
                    'bonus' => floatval($rec->bonus_amount ?? 0),
                ];
            }

            // Group by month for summary
            $monthRecords = $records->groupBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m');
            });

            foreach ($monthRecords as $month => $records) {
                $monthlyHours[$month] = floatval($records->sum('total_hours'));
            }
            ksort($monthlyHours);

            // Generate PDF if requested
            if ($request->input('format') === 'pdf') {
                $pdf = Pdf::loadView('admin.viewattendance.pay_slip_pdf', compact('staff', 'monthlyHours', 'monthFilter', 'dailyRecords'));
                $pdf->setPaper('A4', 'portrait');
                return $pdf->download('payslip_' . $staff->id . '_' . ($monthFilter ?? 'all') . '.pdf');
            }

            return view('admin.viewattendance.pay_slip', compact('staff', 'monthlyHours', 'monthFilter', 'dailyRecords'));
        } catch (\Exception $e) {
            Log::error('Error generating pay slip for staff ID ' . $staffId, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'An error occurred while generating the pay slip.');
        }
    }
}