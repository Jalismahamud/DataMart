<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'base_price',
        'regular_price',
        'discount_price',
        'featured_image',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $product) {
            if (self::query()->exists()) {
                throw new \RuntimeException('Only one product is allowed. Update the existing product instead of creating a new one.');
            }
        });
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function getEffectivePriceAttribute(): float
    {
        $regularPrice = (float) ($this->regular_price ?? $this->base_price ?? 0);
        $discountPrice = (float) ($this->discount_price ?? 0);

        return $discountPrice > 0 && $discountPrice < $regularPrice ? $discountPrice : $regularPrice;
    }
}
