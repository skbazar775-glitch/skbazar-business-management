<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'product_id',
        'stock_quantity',
        'stock_quantity_unit',
        'updated_by',
    ];

    protected $casts = [
        'stock_quantity' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
