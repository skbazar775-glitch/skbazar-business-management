<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'amount',
        'expense_date',
        'notes',
        'created_by',
        'updated_by',
        'status', // ✅ Added
    ];

    protected $casts = [
        'expense_date' => 'date',
        'status' => 'string', // ✅ Optional but useful
    ];
}
