<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'image_path',
        'completed_date',
    ];

    protected $casts = [
        'completed_date' => 'date',
    ];

    // Optional: get a short description if needed
    public function getShortDescriptionAttribute()
    {
        return \Str::limit($this->description, 100);
    }

    // Optional: nice formatted date
    public function getFormattedDateAttribute()
    {
        return $this->completed_date->format('F Y');
    }

    // Optional: badge color helper (for blade usage)
    public function getCategoryColorAttribute()
    {
        return match ($this->category) {
            'Commercial' => 'text-green-400',
            'Industrial' => 'text-yellow-400',
            default => 'text-blue-400',
        };
    }
}
