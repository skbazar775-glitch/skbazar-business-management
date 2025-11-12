<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkCredit extends Model
{
    protected $table = 'sk_credit';

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'note',
        'date',
        'created_at',
        'updated_at',
    ];

    public $timestamps = false; // Keep this to manually manage timestamps

    protected $casts = [
        'date' => 'datetime', // Cast 'date' as datetime to include time
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}