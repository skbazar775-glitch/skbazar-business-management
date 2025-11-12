<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceRecord extends Model
{
    protected $table = 'attendance_records';

    protected $fillable = [
        'staff_id',
        'attendance_date',
        'first_half_in',
        'first_half_out',
        'first_half_status',
        'first_half_late_by',
        'second_half_in',
        'second_half_out',
        'second_half_status',
        'second_half_late_by',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'first_half_in' => 'datetime',
        'first_half_out' => 'datetime',
        'second_half_in' => 'datetime',
        'second_half_out' => 'datetime',
        'first_half_status' => 'string',
        'second_half_status' => 'string',
    ];

public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id', 'id');
    }

    /**
     * Calculate attendance status and late duration
     */
    public static function calculateAttendanceStatus($inTime, $outTime, $half = 'first')
    {
        $startTime = $half === 'first' ? Carbon::createFromTime(9, 0) : Carbon::createFromTime(15, 0);
        $endTime = $half === 'first' ? Carbon::createFromTime(13, 0) : Carbon::createFromTime(19, 0);

        if (!$inTime) {
            return ['status' => 'absent', 'late_by' => null];
        }

        $inCarbon = Carbon::parse($inTime);
        $lateThreshold = $startTime->copy()->addMinutes(15); // 15-minute grace period

        // Calculate late status
        if ($inCarbon->gt($lateThreshold)) {
            $lateBy = $inCarbon->diff($startTime)->format('%H:%I:%S');
            if (!$outTime) {
                return ['status' => 'late', 'late_by' => $lateBy];
            }
        }

        // Check if present for minimum required duration (3 hours)
        if ($outTime) {
            $outCarbon = Carbon::parse($outTime);
            $minDuration = $startTime->copy()->addHours(3);
            if ($outCarbon->gte($minDuration)) {
                return ['status' => 'present', 'late_by' => $inCarbon->gt($lateThreshold) ? $lateBy : null];
            }
            return ['status' => 'absent', 'late_by' => $inCarbon->gt($lateThreshold) ? $lateBy : null];
        }

        return ['status' => 'absent', 'late_by' => $inCarbon->gt($lateThreshold) ? $lateBy : null];
    }
}