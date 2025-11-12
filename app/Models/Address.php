<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'pin_code',
        'district',
        'city',
        'area',
        'landmark',
        'gst_no', // Added to support GST number
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute()
    {
        // Combine address fields into a single string, excluding empty fields
        $addressParts = array_filter([
            $this->area,
            $this->city,
            $this->district,
            $this->pin_code,
            $this->landmark,
        ], function ($part) {
            return !empty(trim($part));
        });

        return implode(', ', $addressParts);
    }
}