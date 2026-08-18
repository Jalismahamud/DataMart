<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['site_name'] ?? 'DadaGarments' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f8f4ef] text-slate-800">
    <div class="max-w-md mx-auto bg-[#f8f4ef] min-h-screen shadow-lg">
        <header class="px-5 pt-6 pb-4 text-center">
            <p class="text-xs uppercase tracking-[0.25em] text-[#8a5a38]">{{ $settings['site_name'] ?? 'DadaGarments' }}</p>
            <h1 class="mt-2 text-3xl font-black text-[#2b1d12]">{{ $settings['hero_title'] ?? 'প্রিয়মণি বোরকা কালেকশন' }}</h1>
        </header>

        <div class="px-4">
            <div class="overflow-hidden rounded-[28px] border border-[#ead8c0] bg-white shadow-sm">
                @if (!empty($products->first()?->featured_image))
                    <img src="{{ asset('storage/' . $products->first()->featured_image) }}" alt="{{ $products->first()->name }}" class="w-full h-[420px] object-cover">
                @else
                    <div class="w-full h-[420px] bg-gradient-to-br from-slate-200 via-stone-100 to-[#dcc6ac] flex items-center justify-center text-2xl font-bold text-[#8a5a38]">
                        Product Image
                    </div>
                @endif
            </div>
        </div>

        <div class="px-4 mt-5">
            @foreach ($products as $product)
                <div class="bg-white rounded-[28px] p-4 shadow-sm border border-[#f0e2d0] mb-5">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-2xl font-black text-[#2b1d12]">{{ $product->name }}</h2>
                            <p class="text-sm text-slate-500 mt-1">{{ $product->short_description ?? 'Premium quality product' }}</p>
                        </div>
                        <div class="flex items-center gap-2 text-[#d97706]">
                            <span class="text-lg">★</span>
                            <span class="font-bold">{{ number_format($product->variations->first()?->price ?? $product->base_price, 0) }} ৳</span>
                        </div>
                    </div>

                    <div class="mt-5 space-y-3">
                        @foreach ($product->variations as $variation)
                            <label class="flex items-center justify-between p-3 border rounded-2xl {{ $variation->is_default ? 'border-[#d97706] bg-[#fff7ed]' : 'border-slate-200' }} cursor-pointer">
                                <div>
                                    <div class="font-semibold text-[#2b1d12]">{{ $variation->name }}</div>
                                    <div class="text-sm text-slate-500">{{ number_format($variation->price, 0) }} ৳</div>
                                </div>
                                <input type="radio" name="variation_{{ $product->id }}" value="{{ $variation->id }}" {{ $variation->is_default ? 'checked' : '' }}>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="px-4 pb-8">
            <div class="bg-white rounded-[28px] p-4 shadow-sm border border-[#f0e2d0]">
                <h3 class="text-2xl font-black text-[#2b1d12] mb-4">Customer Reviews</h3>
                <div class="space-y-4">
                    @foreach ($reviews as $review)
                        <div class="rounded-2xl border border-slate-200 p-3">
                            <div class="flex items-center gap-3">
                                @if ($review->image)
                                    <img src="{{ asset('storage/' . $review->image) }}" alt="{{ $review->name }}" class="w-14 h-14 rounded-full object-cover">
                                @endif
                                <div>
                                    <div class="font-bold text-[#2b1d12]">{{ $review->name }}</div>
                                    <div class="text-yellow-500">{{ str_repeat('★', $review->rating) }}</div>
                                </div>
                            </div>
                            <p class="mt-3 text-sm text-slate-600">{{ $review->text }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html>
