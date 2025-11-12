<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name',
        'email',
        'contact_number',
        'location',
        'amount',
    ];

    public function credits()
    {
        return $this->hasMany(SupplierCredit::class, 'supplier_id');
    }
}
