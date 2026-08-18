<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::firstOrCreate(
            ['slug' => 'full-set-borka'],
            [
                'name' => 'ফুল সেট বোরকা',
                'description' => 'প্রিমিয়াম ডিজিটাল প্রিন্ট বোরকা ও হিজাব সেট। সম্পূর্ণ নতুন কালেকশন, আরামদায়ক কাপড়, আলংকারিক লেস ওয়ার্ক ও ফিট ও ফ্লোয়িং ডিজাইন।',
                'base_price' => 3000,
                'regular_price' => 3000,
                'discount_price' => 2550,
                'featured_image' => '1.webp',
                'is_active' => true,
            ]
        );

        if ($product->variations()->count() === 0) {
            $sizes = ['৫০', '৫২', '৫৪', '৫৬', '৫৮'];
            $colors = ['কালো', 'মেরুন', 'অলিভ'];

            foreach ($sizes as $index => $size) {
                foreach ($colors as $colorIndex => $color) {
                    $product->variations()->create([
                        'name' => 'ফুল সেট',
                        'short_description' => 'দুই পার্ট কটি বোরকা + হুডি হিজাব ও নিকার',
                        'price' => 2550,
                        'regular_price' => 3000,
                        'discount_price' => 2550,
                        'size' => $size,
                        'color' => $color,
                        'is_default' => $index === 0 && $colorIndex === 0,
                    ]);
                }
            }
        }

        if ($product->images()->count() === 0) {
            $product->images()->createMany([
                ['image_path' => '1.webp', 'is_featured' => true, 'sort_order' => 1],
                ['image_path' => '2.webp', 'is_featured' => false, 'sort_order' => 2],
                ['image_path' => '3.webp', 'is_featured' => false, 'sort_order' => 3],
            ]);
        }

        if (Review::count() === 0) {
            foreach (['1.png', '1.png', '1.png'] as $image) {
                Review::create([
                    'image' => $image,
                    'rating' => 5,
                ]);
            }
        }
    }
}
