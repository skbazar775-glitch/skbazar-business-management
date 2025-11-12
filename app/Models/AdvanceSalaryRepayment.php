<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceSalaryRepayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'advance_salary_id',
        'emi_number',
        'paid_amount',
        'paid_date',
        'note',
    ];

    public function advanceSalary()
    {
        return $this->belongsTo(AdvanceSalary::class);
    }
}