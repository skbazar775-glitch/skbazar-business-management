<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceSalary extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'amount',
        'total_emi',
        'monthly_emi',
        'advance_date',
        'note',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function repayments()
    {
        return $this->hasMany(AdvanceSalaryRepayment::class);
    }

    public function getRemainingBalanceAttribute()
    {
        $paid = $this->repayments()->sum('paid_amount');
        return $this->amount - $paid;
    }

    public function getPaidEmisAttribute()
    {
        return $this->repayments()->count();
    }
}