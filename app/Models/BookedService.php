<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookedService extends Model
{
    protected $table = 'booked_services';

    protected $fillable = [
        'service_id',
        'user_id',
        'address_id',
        'date',
        'status',
        'staff_id',
    ];

    protected $casts = [
        'date' => 'datetime',
        'status' => 'integer',
    ];

    public function service()
    {
        return $this->belongsTo(ServiceList::class, 'service_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            0 => 'Pending',
            1 => 'Confirmed',
            2 => 'Staff Assigned',
            3 => 'Running Work',
            4 => 'Work Done',
            5 => 'Canceled',
        ];
        return $statuses[$this->status] ?? 'Unknown';
    }
}