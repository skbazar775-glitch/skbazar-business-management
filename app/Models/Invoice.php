<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'user_id',
        'address_id',
        'customer_name',
        'customer_address',
        'customer_mobile',
        'customer_gst_no',
        'invoice_number',
        'total_price',
        'total_gst',
        'sgst',
        'cgst',
        'discount',
        'advance_payment',
        'final_amount',
        'total_in_words',
        'payment_terms',
        'payment_mode',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'address_id' => 'integer',
        'total_price' => 'float',
        'total_gst' => 'float',
        'sgst' => 'float',
        'cgst' => 'float',
        'discount' => 'float',
        'advance_payment' => 'float',
        'final_amount' => 'float',
        'payment_terms' => 'integer',
        'payment_mode' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function getPaymentTermsTextAttribute()
    {
        return match ($this->payment_terms) {
            0 => 'Fully Advance',
            1 => 'Half Advance',
            2 => 'Due',
            default => 'Unknown',
        };
    }

    public function getPaymentModeTextAttribute()
    {
        return match ($this->payment_mode) {
            0 => 'Mobile Banking',
            1 => 'Online Payment',
            2 => 'Cash Payment',
            3 => 'Cash on Delivery',
            default => 'Unknown',
        };
    }
}
?>