<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UpiOrder extends Model
{
    use HasFactory;

    protected $table = 'upi_orders';

    protected $fillable = [
        'user_id',
        'order_id',
        'customer_mobile',
        'amount',
        'payment_url',
        'is_active',
        'response_json',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'response_json' => 'array',
    ];

    // Optional: define relationship to User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
