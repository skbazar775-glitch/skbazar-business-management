<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'image',
        'price_e',
        'price_s',
        'price_b',
        'price_p',
        'status',
        'created_by',
        'updated_by',
        'sellin_quantity',
        'sellin_quantity_unit',
    ];

    protected $casts = [
        'price_e' => 'float',
        'price_s' => 'float',
        'price_b' => 'float',
        'price_p' => 'float',
        'sellin_quantity' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->created_by = auth()->id();
            $product->updated_by = auth()->id();
        });

        static::updating(function ($product) {
            $product->updated_by = auth()->id();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            0 => 'Active',
            1 => 'Inactive',
            2 => 'Out of Stock',
            3 => 'Bestseller',
            4 => 'Offer',
             5 => 'New',
            default => 'Unknown',
        };
    }
}