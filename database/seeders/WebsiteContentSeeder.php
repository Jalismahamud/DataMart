<?php

namespace Database\Seeders;

use App\Models\Product;
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

        if ($product->sizes()->count() === 0) {
            $sizes = ['৫০', '৫২', '৫৪'];
            foreach ($sizes as $size) {
                $product->sizes()->create(['name' => $size]);
            }
        }

        if ($product->colors()->count() === 0) {
            $colors = ['কালো', 'মেরুন', 'অলিভ'];
            foreach ($colors as $color) {
                $product->colors()->create(['name' => $color]);
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
