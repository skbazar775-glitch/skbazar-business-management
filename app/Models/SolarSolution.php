<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolarSolution extends Model
{
    protected $fillable = [
        'title',
        'description',
        'color_class',
        'image_path',
    ];
}