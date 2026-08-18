<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
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
            'product_cta_text' => 'অর্ডার করতে এখানে ক্লিক করুন',
            'review_title' => 'আমাদের গ্রাহকদের মতামত',
            'delivery_inside_dhaka' => 70,
            'delivery_outside_dhaka' => 120,
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
