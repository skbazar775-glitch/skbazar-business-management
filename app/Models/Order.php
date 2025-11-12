<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'unique_order_id',
        'user_id',
        'address_id',
        'total_amount',
        'payment_method',
        'payment_status', // 0 = unpaid, 1 = paid, 2 = failed
        'status',        // 0 = pending, 1 = confirmed, 2 = packed, 3 = shipped, 4 = delivered, 5 = canceled
    ];

    /**
     * Relationship: An Order belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship: An Order has many OrderItems.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Accessor: Get human-readable payment status.
     */
    public function getPaymentStatusTextAttribute(): string
    {
        return match ($this->payment_status) {
            0 => 'Unpaid',
            1 => 'Paid',
            2 => 'Failed',
            default => 'Unknown',
        };
    }

    /**
     * Accessor: Get human-readable order status.
     */
    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            0 => 'Pending',
            1 => 'Confirmed',
            2 => 'Packed',
            3 => 'Shipped',
            4 => 'Delivered',
            5 => 'Canceled',
            default => 'Unknown',
        };
    }
}