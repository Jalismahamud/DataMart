<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'invoice_number',
        'customer_name',
        'phone',
        'address',
        'city',
        'delivery_zone',
        'product_id',
        'variation_id',
        'selected_size',
        'selected_color',
        'quantity',
        'unit_price',
        'shipping_cost',
        'total_amount',
        'payment_method',
        'notes',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $order) {
            if (empty($order->invoice_number)) {
                $order->invoice_number = self::generateInvoiceNumber();
            }
        });
    }

    public static function generateInvoiceNumber(): string
    {
        do {
            $invoice = 'INV-' . now()->format('Ymd') . '-' . strtoupper(str()->random(6));
        } while (self::query()->where('invoice_number', $invoice)->exists());

        return $invoice;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }
}
