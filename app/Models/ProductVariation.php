<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'regular_price',
        'discount_price',
        'size',
        'color',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getEffectivePriceAttribute(): float
    {
        $regularPrice = (float) ($this->regular_price ?? $this->price ?? 0);
        $discountPrice = (float) ($this->discount_price ?? 0);

        return $discountPrice > 0 && $discountPrice < $regularPrice ? $discountPrice : $regularPrice;
    }
}
