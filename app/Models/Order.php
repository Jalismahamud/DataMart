<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'phone',
        'address',
        'city',
        'delivery_zone',
        'product_id',
        'variation_id',
        'quantity',
        'unit_price',
        'shipping_cost',
        'total_amount',
        'payment_method',
        'notes',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variation()
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }
}
