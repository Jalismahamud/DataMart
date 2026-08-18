<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
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
            'delivery_inside_dhaka' => 70,
            'delivery_outside_dhaka' => 120,
        ];

        $settings = array_replace($defaultSettings, SiteSetting::pluck('value', 'key')->toArray());

        $products = Product::with('variations')->where('is_active', true)->get();
        if ($products->isEmpty()) {
            $product = new Product([
                'id' => 1,
                'name' => 'ফুল সেট বোরকা',
                'short_description' => 'দুই পার্ট কটি বোরকা + হুডি হিজাব ও নিকার',
                'base_price' => 3000,
                'regular_price' => 3000,
                'discount_price' => 2550,
                'is_active' => true,
            ]);

            $product->setRelation('images', collect([
                (object) ['image_path' => '1.webp'],
            ]));

            $product->setRelation('variations', collect([
                new ProductVariation([
                    'id' => 1,
                    'product_id' => 1,
                    'name' => 'ফুল সেট',
                    'size' => '50',
                    'color' => 'কালো',
                    'price' => 2550,
                    'regular_price' => 3000,
                    'discount_price' => 2550,
                    'is_default' => true,
                ]),
                new ProductVariation([
                    'id' => 2,
                    'product_id' => 1,
                    'name' => 'ফুল সেট',
                    'size' => '52',
                    'color' => 'মেরুন',
                    'price' => 2550,
                    'regular_price' => 3000,
                    'discount_price' => 2550,
                    'is_default' => false,
                ]),
            ]));

            $products = collect([$product]);
        }

        $reviews = Review::latest()->get();
        if ($reviews->isEmpty()) {
            $reviews = collect([
                new Review([
                    'id' => 1,
                    'name' => 'Customer 1',
                    'text' => 'Very nice quality and comfortable to wear.',
                    'image' => '1.png',
                    'rating' => 5,
                    'is_active' => true,
                ]),
                new Review([
                    'id' => 2,
                    'name' => 'Customer 2',
                    'text' => 'Delivery was fast and the design is beautiful.',
                    'image' => '1.png',
                    'rating' => 5,
                    'is_active' => true,
                ]),
            ]);
        }

        return view('frontend.home', compact('settings', 'products', 'reviews'));
    }

    public function getColorsBySize(Request $request, Product $product)
    {
        $size = $request->query('size');
        
        if (!$size) {
            return response()->json(['colors' => []], 200);
        }

        $colors = $product->getColorsBySize($size);
        
        return response()->json(['colors' => $colors], 200);
    }

    public function getVariationId(Request $request, Product $product)
    {
        $size = $request->query('size');
        $color = $request->query('color');
        
        if (!$size || !$color) {
            return response()->json(['variation_id' => null], 400);
        }

        $variation = $product->getVariationByAttributes($size, $color);
        
        return response()->json([
            'variation_id' => $variation?->id,
            'price' => $variation?->price,
            'regular_price' => $variation?->regular_price,
            'discount_price' => $variation?->discount_price,
        ], 200);
    }
}
