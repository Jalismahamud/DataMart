<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'short_description',
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

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function defaultVariation()
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
        return $this->variations()
            ->whereNotNull('size')
            ->distinct()
            ->pluck('size')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    public function getColorsBySize($size)
    {
        return $this->variations()
            ->where('size', $size)
            ->whereNotNull('color')
            ->distinct()
            ->pluck('color')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    public function getVariationByAttributes($size, $color)
    {
        return $this->variations()
            ->where('size', $size)
            ->where('color', $color)
            ->first();
    }
}
