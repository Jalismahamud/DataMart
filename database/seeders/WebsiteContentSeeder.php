<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Review;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class WebsiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'DadaGarments',
            'site_logo' => 'asset/images/logo.png',
            'phone' => '01798887282',
            'hero_title' => 'প্রিমিয়াম বোরকা কালেকশন',
            'hero_subtitle' => 'আমাদের নিজস্ব ফ্যাক্টরিতে উৎপাদিত',
            'hero_badge' => 'সম্পূর্ণ নতুন কালেকশন',
            'product_title' => 'ডিজিটাল প্রিন্ট বোরকা ও হিজাব সেট',
            'product_subtitle' => 'পর্দা ও স্টাইল এখন হবে একসাথে',
            'product_regular_price' => 3000,
            'product_offer_price' => 2550,
            'product_cta_text' => 'অর্ডার করতে এখান ক্লিক করুন',
            'review_title' => 'আমাদের গ্রাহকদের মতামত',
            'review_image_1' => 'asset/images/review/1.png',
            'review_image_2' => 'asset/images/review/1.png',
            'review_image_3' => 'asset/images/review/1.png',
            'delivery_inside_dhaka' => 70,
            'delivery_outside_dhaka' => 120,
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $product = Product::firstOrCreate(
            ['slug' => 'full-set-borka'],
            [
                'name' => 'ফুল সেট বোরকা',
                'description' => 'প্রিমিয়াম ডিজিটাল প্রিন্ট বোরকা ও হিজাব সেট।',
                'base_price' => 3000,
                'regular_price' => 3000,
                'discount_price' => 2550,
                'featured_image' => '1.webp',
                'is_active' => true,
            ]
        );

        if ($product->variations()->count() === 0) {
            $variations = [
                ['name' => 'ফুল সেট', 'short_description' => 'দুই পার্ট কটি বোরকা + হুডি হিজাব ও নিকার', 'size' => '৫০', 'color' => 'কালো', 'price' => 2550, 'regular_price' => 3000, 'discount_price' => 2550, 'is_default' => true],
                ['name' => 'ফুল সেট', 'short_description' => 'দুই পার্ট কটি বোরকা + হুডি হিজাব ও নিকার', 'size' => '৫২', 'color' => 'মেরুন', 'price' => 2550, 'regular_price' => 3000, 'discount_price' => 2550, 'is_default' => false],
                ['name' => 'ফুল সেট', 'short_description' => 'দুই পার্ট কটি বোরকা + হুডি হিজাব ও নিকার', 'size' => '৫৪', 'color' => 'অলিভ', 'price' => 2550, 'regular_price' => 3000, 'discount_price' => 2550, 'is_default' => false],
            ];

            foreach ($variations as $variation) {
                $product->variations()->create([
                    'name' => $variation['name'],
                    'short_description' => $variation['short_description'],
                    'size' => $variation['size'],
                    'color' => $variation['color'],
                    'price' => $variation['price'],
                    'regular_price' => $variation['regular_price'],
                    'discount_price' => $variation['discount_price'],
                    'is_default' => $variation['is_default'],
                ]);
            }
        }

        if (Review::count() === 0) {
            foreach ([
                '1.png',
                '1.png',
                '1.png',
            ] as $image) {
                Review::create([
                    'image' => $image,
                    'rating' => 5,
                ]);
            }
        }
    }
}
