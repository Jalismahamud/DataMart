<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Review;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::pluck('value', 'key')->toArray();
        $products = Product::with('variations')->where('is_active', true)->get();
        $reviews = Review::where('is_active', true)->latest()->get();

        return view('frontend.home', compact('settings', 'products', 'reviews'));
    }
}
