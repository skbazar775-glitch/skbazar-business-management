<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryRecord extends Model
{
    use HasFactory;

    protected $table = 'salary_records';

    protected $fillable = [
        'staff_id',
        'date',
        'first_half_hours',
        'second_half_hours',
        'total_hours',
        'daily_salary',
        'bonus_amount',
    ];

    protected $casts = [
        'date' => 'date',
        'first_half_hours' => 'decimal:2',
        'second_half_hours' => 'decimal:2',
        'total_hours' => 'decimal:2',
        'daily_salary' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}   