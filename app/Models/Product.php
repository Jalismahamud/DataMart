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

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function getDefaultVariation()
    {
        return $this->variations()->where('is_default', true)->first()
            ?? $this->variations()->first();
    }

    public function syncDefaultVariation(?int $selectedVariationId = null): void
    {
        $this->variations()->update(['is_default' => false]);

        if ($selectedVariationId) {
            $this->variations()->whereKey($selectedVariationId)->update(['is_default' => true]);
            return;
        }

        $firstVariation = $this->variations()->first();
        if ($firstVariation) {
            $firstVariation->update(['is_default' => true]);
        }
    }

    public function getEffectivePriceAttribute(): float
    {
        $regularPrice = (float) ($this->regular_price ?? $this->base_price ?? 0);
        $discountPrice = (float) ($this->discount_price ?? 0);

        return $discountPrice > 0 && $discountPrice < $regularPrice ? $discountPrice : $regularPrice;
    }

    public function getUniqueSizes()
    {
        return $this->sizes()
            ->pluck('size')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    public function getColorsBySize($size)
    {
        return $this->colors()
            ->pluck('color')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    public function getVariationByAttributes($size, $color)
    {
        return null;
    }
}
