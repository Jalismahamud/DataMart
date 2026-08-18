<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $defaultSettings = [
            'site_name' => 'DadaGarments',
            'site_logo' => asset('asset/images/logo.png'),
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
            'review_image_1' => 'asset/images/review/1.png',
            'review_image_2' => 'asset/images/review/1.png',
            'review_image_3' => 'asset/images/review/1.png',
            'delivery_inside_dhaka' => 70,
            'delivery_outside_dhaka' => 120,
        ];

        $settings = array_replace($defaultSettings, SiteSetting::pluck('value', 'key')->toArray());

        $products = Product::with(['sizes', 'colors', 'images'])->where('is_active', true)->get();

        $reviews = Review::latest()->get();

        return view('frontend.home', compact('settings', 'products', 'reviews'));
    }
}
