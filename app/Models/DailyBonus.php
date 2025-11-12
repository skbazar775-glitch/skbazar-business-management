<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyBonus extends Model
{
    use HasFactory;

    protected $table = 'daily_bonuses';

    protected $fillable = [
        'staff_id',
        'date',
        'bonus_amount',
    ];

    protected $casts = [
        'date' => 'date',
        'bonus_amount' => 'decimal:2',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}