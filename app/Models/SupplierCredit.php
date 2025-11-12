<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierCredit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sk_supplier_credit';

    protected $fillable = [
        'supplier_id',
        'type',
        'amount',
        'date',
        'note',
    ];

    protected $casts = [
        'date' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}