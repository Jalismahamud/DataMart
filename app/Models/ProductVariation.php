<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'regular_price',
        'discount_price',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'price' => 'decimal:2',
        'regular_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
