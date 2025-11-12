<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceList extends Model
{
    protected $table = 'service_list';

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}