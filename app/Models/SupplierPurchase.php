<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierPurchase extends Model
{
    use HasFactory;

    protected $table = 'supplier_purchase';

    protected $fillable = [
        'supplier_id',
        'product_id',
        'quantity',
        'purchase_price_incl_gst',
        'purchase_price_excl_gst',
        'gst_percent',
        'gst_value_per_qty',
        'total_gst_value',
        'total_price',
        'total_price_without_gst',
        'invoice_no',
        'invoice_date',
        'total_invoice_value',
        'transportation_costs_incl',
        'total_invoice_value_incl_transportation',
        'transportation_costs_excl',
        'total_payment',
        'after_payment_total_value',
    ];

    protected $casts = [
        'invoice_date' => 'date', // Cast invoice_date to Carbon
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}