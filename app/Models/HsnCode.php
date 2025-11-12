<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HsnCode extends Model
{
    use HasFactory;

    protected $table = 'hsn_codes';

    protected $fillable = [
        'code',
        'description',
        'gst_rate',
    ];

    protected $casts = [
        'gst_rate' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
?>