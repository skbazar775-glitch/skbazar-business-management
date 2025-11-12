<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $table = 'invoice_items';

    protected $fillable = [
        'invoice_id',
        'product_name',
        'serial_number',
        'quantity',
        'hsn_code',
        'rate_with_gst',
        'gst_percent',
        'rate_without_gst',
        'price_without_gst',
        'gst_value',
        'total_price',
    ];

    protected $casts = [
        'invoice_id' => 'integer',
        'quantity' => 'integer',
        'rate_with_gst' => 'float',
        'gst_percent' => 'integer',
        'rate_without_gst' => 'float',
        'price_without_gst' => 'float',
        'gst_value' => 'float',
        'total_price' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getGstPercentTextAttribute()
    {
        return match ($this->gst_percent) {
            0 => '0%',
            1 => '12%',
            2 => '18%',
            3 => '28%',
            default => 'Unknown',
        };
    }
}
?>