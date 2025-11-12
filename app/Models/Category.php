<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'created_by',
        'updated_by',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->created_by = auth()->id();
            $category->updated_by = auth()->id();
            $category->slug = $category->slug ?? Str::slug($category->title);
        });

        static::updating(function ($category) {
            $category->updated_by = auth()->id();
            if (!$category->slug) {
                $category->slug = Str::slug($category->title);
            }
        });
    }
}