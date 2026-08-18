<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['site_name'] ?? 'DadaGarments' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Anek+Bangla:wght@400;500;600;700;800&family=Noto+Sans+Bengali:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/style.css') }}">
</head>

<body>
    @php
        $productList = $products->count() ? $products : collect([
            (object) [
                'id' => 1,
                'name' => 'ফুল সেট বোরকা',
                'short_description' => 'দুই পার্ট কটি বোরকা + হুডি হিজাব ও নিকার',
                'base_price' => 3000,
                'regular_price' => 3000,
                'discount_price' => 2550,
                'defaultVariation' => (object) ['price' => 2550, 'discount_price' => 2550],
            ]
        ]);

        $firstProduct = $productList->first();
        $firstSizes = $firstProduct && method_exists($firstProduct, 'getUniqueSizes') ? $firstProduct->getUniqueSizes() : ['৫০', '৫২', '৫৪', '৫৬', '৫৮'];
        $firstColors = $firstProduct && method_exists($firstProduct, 'getColorsBySize') && !empty($firstSizes) ? $firstProduct->getColorsBySize($firstSizes[0]) : ['কালো', 'মেরুন', 'অলিভ'];

        $reviewItems = $reviews->count() ? $reviews : collect([
            (object) ['image' => '1.png', 'rating' => 5],
            (object) ['image' => '1.png', 'rating' => 5],
            (object) ['image' => '1.png', 'rating' => 5],
        ]);

        $productTitle = $settings['product_title'] ?? 'ডিজিটাল প্রিন্ট বোরকা ও হিজাব সেট';
        $productSubtitle = $settings['product_subtitle'] ?? 'পর্দা ও স্টাইল এখন হবে একসাথে';
        $regularPrice = (float) ($settings['product_regular_price'] ?? ($firstProduct->regular_price ?? 3000));
        $offerPrice = (float) ($settings['product_offer_price'] ?? (($firstProduct && method_exists($firstProduct, 'getDefaultVariation')) ? ($firstProduct->getDefaultVariation()?->discount_price ?? $firstProduct->getDefaultVariation()?->price ?? 2550) : 2550));
        $ctaText = $settings['product_cta_text'] ?? 'অর্ডার করতে এখানে ক্লিক করুন';
        $heroTitle = $settings['hero_title'] ?? 'প্রিমিয়াম বোরকা কালেকশন';
        $heroSubtitle = $settings['hero_subtitle'] ?? 'আমাদের নিজস্ব ফ্যাক্টরিতে উৎপাদিত';
        $heroBadge = $settings['hero_badge'] ?? 'সম্পূর্ণ নতুন কালেকশন';
        $reviewTitle = $settings['review_title'] ?? 'আমাদের গ্রাহকদের মতামত';
        $deliveryOutside = (float) ($settings['delivery_outside_dhaka'] ?? 120);
        $deliveryInside = (float) ($settings['delivery_inside_dhaka'] ?? 70);
    @endphp

    <div class="mobile-wrapper">
        <header class="top-header">
            <a href="#" class="header-logo">
                <img src="{{ asset('asset/images/logo.png') }}" alt="DadaMart" style="height: 40px; width: auto; margin-right: 0;">
            </a>
            <a href="#order-form" class="btn-call text-decoration-none">
                <i class="fa fa-shopping-cart"></i> অর্ডার করুন
            </a>
        </header>

        <div class="banner-title-section text-center">
            <div class="banner-subtitle">{{ $heroSubtitle }}</div>
            <h1 class="banner-title-main">{{ $heroTitle }}</h1>
            <div class="banner-underline">
                <span class="line-long"></span>
                <span class="line-short"></span>
            </div>
        </div>

        <div class="hero-img-container position-relative">
            <div class="hero-badge" style="z-index: 10;"> {{ $heroBadge }} </div>
            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($productList->take(3) as $index => $product)
                        @php
                            $heroImage = asset('asset/images/products/' . (($index % 3) + 1) . '.webp');
                            if (($product->images ?? null) && $product->images->isNotEmpty()) {
                                $heroImage = asset('images/products/' . $product->images->first()->image_path);
                            } elseif (!empty($product->featured_image ?? null)) {
                                $heroImage = asset('images/products/' . $product->featured_image);
                            }
                        @endphp
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ $heroImage }}" class="d-block w-100 hero-img" alt="{{ $product->name ?? 'Product' }}">
                        </div>
                    @endforeach
                </div>
                <div class="carousel-indicators mt-3 mb-2" style="position: static;">
                    @foreach ($productList->take(3) as $index => $product)
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            </div>
            <hr style="border-top: 2px solid #e0e0e0; margin: 5px 0 15px 0;">
        </div>

        <div class="px-3 pb-2 text-center mt-3">
            <a href="#order-form" class="btn-action-primary">
                <i class="fa-solid fa-cart-shopping"></i> {{ $ctaText }}
            </a>
            <div class="text-muted mt-2 mb-3" style="font-size: 0.85rem; font-style: italic;">
                * স্টোক সীমিত, দ্রুত আপনার পছন্দের বোরকাটি বেছে নিন
            </div>
        </div>

        <div class="px-3 mt-3">
            <h2 class="product-title-new">{{ $productTitle }}</h2>
            <div class="product-subtitle-new">{{ $productSubtitle }}</div>
        </div>

        <div class="price-box-new">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <div class="regular-price">রেগুলার মূল্য: <span>{{ number_format($regularPrice, 0, '.', ',') }}/- টাকা</span></div>
                <div class="offer-badge"><i class="fa-solid fa-fire"></i> আজকের অফার</div>
            </div>

            <div class="new-price-container-new">
                <span class="new-price-lg">{{ number_format($offerPrice, 0, '.', ',') }}</span> <span class="taka-text-sm">টাকা</span>
            </div>

            <div class="d-flex gap-2 mb-2">
                <span class="badge-stock"><i class="fa-solid fa-bolt"></i> সীমিত স্টক</span>
                <span class="badge-cod"><i class="fa-solid fa-check"></i> COD Available</span>
            </div>

            <div class="price-features">
                <span><i class="fa-solid fa-check"></i> প্রিমিয়াম কোয়ালিটি</span>
                <span><i class="fa-solid fa-check"></i> সহজ রিটার্ন</span>
                <span><i class="fa-solid fa-check"></i> দ্রুত ডেলিভারি</span>
            </div>
        </div>

        <div class="px-3 mt-4">
            <h3 class="features-heading">কেন এই বোরকা বেস্ট:</h3>
            <ul class="features-list-new">
                <li><span>🖤</span> বোরকা: এক্সক্লুসিভ দুই পার্ট কটি বোরকা</li>
                <li><span>✨</span> হাতাই ও বডি জুড়ে সুন্দর সিকোয়েন্স লেস ওয়ার্ক</li>
                <li><span>👗</span> বোরকা কাপড়: অরিজিনাল দুবাই চেরি (ঘের: ১৫০ ইঞ্চি প্লাস)</li>
                <li><span>🧕</span> হিজাব / নিকার: হুডি হিজাব ডিজাইন (চারদিকে এলিগ্যান্ট লেস ওয়ার্ক)</li>
                <li><span>🌸</span> ডিজাইন: পিছনে দুই পার্ট, সামনে দুই পার্ট</li>
                <li><span>🌿</span> কাপড়: অরিজিনাল সফট শিফন জর্জেট (হালকা ও অত্যন্ত আরামদায়ক)</li>
            </ul>
        </div>

        <div class="px-3 mb-4">
            <a href="#order-form" class="btn-action-primary">
                <i class="fa-solid fa-cart-shopping"></i> {{ $ctaText }}
            </a>
        </div>

        <div class="px-3 mt-3 mb-4 text-center">
            <h3 class="review-title-new">{{ $reviewTitle }}</h3>
            <div class="review-underline">
                <span></span><span></span><span></span><span></span><span></span>
            </div>

            <div id="reviewCarousel" class="carousel slide mt-4" data-bs-ride="carousel">
                <div class="carousel-inner review-carousel-inner">
                    @foreach ($reviewItems as $index => $review)
                        @php
                            $reviewImage = asset('asset/images/review/1.png');
                            $reviewImagePath = $review->image ?? '1.png';

                            if (is_string($reviewImagePath) && $reviewImagePath !== '' && !\Illuminate\Support\Str::startsWith($reviewImagePath, 'http')) {
                                $reviewImage = \Illuminate\Support\Str::startsWith($reviewImagePath, 'asset/')
                                    ? asset($reviewImagePath)
                                    : asset('images/reviews/' . $reviewImagePath);
                            }
                        @endphp
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <img src="{{ $reviewImage }}" class="d-block w-100 review-img-new" alt="Review {{ $index + 1 }}">
                        </div>
                    @endforeach
                </div>
                <div class="carousel-indicators mt-3 mb-2" style="position: static;">
                    @foreach ($reviewItems as $index => $review)
                        <button type="button" data-bs-target="#reviewCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="px-3 mb-4">
            <a href="#order-form" class="btn-action-primary">
                <i class="fa-solid fa-cart-shopping"></i> {{ $ctaText }}
            </a>
        </div>

        <div class="whatsapp-banner">
            <div class="whatsapp-icon-top">📱</div>
            <h4>প্রয়োজনে হোয়াটসঅ্যাপ করুন</h4>
            <p>সাইজ, কালার বা যেকোনো প্রশ্নে আমরা সাহায্য করতে প্রস্তুত</p>
            <a href="https://wa.me/{{ preg_replace('/\D+/', '', $settings['phone'] ?? '8801798887282') }}" class="btn-whatsapp">
                <i class="fa-brands fa-whatsapp"></i> {{ $settings['phone'] ?? '01798887282' }}
            </a>
        </div>

        <div class="form-title-section text-center">
            <div class="form-subtitle-small">অর্ডার করুন</div>
            <h2 class="form-title-main">নিচের ফর্মটি পূরণ করুন</h2>
            <div class="review-underline mt-2">
                <span></span><span></span><span></span>
            </div>
        </div>

        <div id="order-form" class="form-section-new">
            <form id="checkoutForm">
                <div class="form-step">
                    <label class="step-label">① পণ্য বাছাই করুন</label>

                    @foreach ($productList as $index => $product)
                        @php
                            $productDefault = method_exists($product, 'getDefaultVariation') ? $product->getDefaultVariation() : null;
                            $productPrice = (float) (($productDefault->discount_price ?? 0) > 0 ? ($productDefault->discount_price ?? $productDefault->price) : ($productDefault->price ?? $product->base_price ?? 2550));
                        @endphp
                        <label class="product-card {{ $index === 0 ? 'selected' : '' }}" onclick="selectProduct(this, {{ $productPrice }})">
                            @if ($index === 0)
                                <div class="popular-badge">সর্বাধিক জনপ্রিয়</div>
                            @endif
                            <input type="radio" name="product" value="{{ $product->id ?? 'product-' . $index }}" {{ $index === 0 ? 'checked' : '' }} class="d-none">
                            <div class="d-flex align-items-center w-100">
                                <div class="product-icon">⭐</div>
                                <div class="product-details">
                                    <h5>{{ $product->name ?? 'ফুল সেট বোরকা' }}</h5>
                                    <p>{{ \Illuminate\Support\Str::limit($product->short_description ?? 'Premium product', 70) }}</p>
                                </div>
                                <div class="product-right">
                                    <div class="product-price">৳{{ number_format($productPrice, 0, '.', ',') }}</div>
                                    <div class="radio-indicator {{ $index === 0 ? '' : 'empty' }}">{{ $index === 0 ? '<i class="fa-solid fa-check"></i>' : '' }}</div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <div class="form-step">
                    <label class="step-label">② কালার বাছাই করুন</label>
                    <div class="size-grid-5">
                        @foreach (array_values($firstColors ?: ['কালো', 'মেরুন', 'অলিভ']) as $index => $color)
                            <label class="size-box {{ $index === 0 ? 'active' : '' }}" onclick="selectColor(this)">
                                <input type="radio" name="color" value="{{ $color }}" {{ $index === 0 ? 'checked' : '' }} class="d-none">
                                {{ $color }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-step">
                    <label class="step-label">③ সাইজ বাছাই করুন <span class="step-sub">(লং সাইজ ইঞ্চিতে)</span></label>
                    <div class="size-grid-5">
                        @foreach (array_values($firstSizes ?: ['৫০', '৫২', '৫৪', '৫৬', '৫৮']) as $index => $size)
                            <label class="size-box {{ $index === 0 ? 'active' : '' }}" onclick="selectSize(this)">
                                <input type="radio" name="size" value="{{ $size }}" {{ $index === 0 ? 'checked' : '' }} class="d-none">
                                {{ $size }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-step">
                    <label class="step-label">④ পরিমাণ</label>
                    <div class="d-flex align-items-center gap-4 mt-2 mb-2">
                        <div class="qty-control">
                            <button type="button" class="qty-btn minus" onclick="decreaseQty()">-</button>
                            <input type="text" id="qty" class="qty-input-new" value="১" readonly>
                            <button type="button" class="qty-btn plus" onclick="increaseQty()">+</button>
                        </div>
                        <div class="qty-total-text">
                            মোট: <span id="qtyTotal">৳{{ number_format($offerPrice, 0, '.', ',') }}</span>
                        </div>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">④ আপনার নাম</label>
                    <input type="text" class="form-control-custom" placeholder="আপনার নাম লিখুন..." required>
                </div>

                <div class="form-group-custom">
                    <input type="tel" class="form-control-custom" placeholder="01XXXXXXXXX" required>
                </div>

                <div class="form-group-custom">
                    <textarea class="form-control-custom" rows="3" placeholder="আপনার সম্পূর্ণ ঠিকানা লিখুন..." required></textarea>
                </div>

                <div class="form-step">
                    <label class="step-label">④ ডেলিভারি এলাকা</label>
                    <label class="product-card selected delivery-new-card" onclick="selectDelivery(this, {{ $deliveryOutside }}, 'ঢাকার বাইরে')">
                        <input type="radio" name="delivery_area" class="d-none" value="outside" checked>
                        <div class="d-flex align-items-center w-100">
                            <div class="delivery-icon-new">🚚</div>
                            <div class="product-details">
                                <h5>ঢাকার সিটির বাইরে</h5>
                                <p>ডেলিভারি চার্জ: {{ (int) $deliveryOutside }}৳</p>
                            </div>
                            <div class="product-right">
                                <div class="radio-indicator"><i class="fa-solid fa-check"></i></div>
                            </div>
                        </div>
                    </label>
                    <label class="product-card delivery-new-card" onclick="selectDelivery(this, {{ $deliveryInside }}, 'ঢাকার ভিতর')">
                        <input type="radio" name="delivery_area" class="d-none" value="inside">
                        <div class="d-flex align-items-center w-100">
                            <div class="delivery-icon-new">🏢</div>
                            <div class="product-details">
                                <h5>ঢাকার সিটির ভিতর</h5>
                                <p>ডেলিভারি চার্জ: {{ (int) $deliveryInside }}৳</p>
                            </div>
                            <div class="product-right">
                                <div class="radio-indicator empty"></div>
                            </div>
                        </div>
                    </label>
                </div>

                <div class="form-step">
                    <label class="step-label">⑤ পেমেন্ট পদ্ধতি</label>
                    <label class="product-card selected mb-0" style="cursor: default;">
                        <input type="radio" name="payment_method" class="d-none" value="cod" checked>
                        <div class="d-flex align-items-center w-100">
                            <div class="delivery-icon-new">💵</div>
                            <div class="product-details">
                                <h5>ক্যাশ অন ডেলিভারি</h5>
                                <p>পণ্য পেয়ে টাকা দিন</p>
                            </div>
                        </div>
                    </label>
                </div>

                <div class="order-summary-box">
                    <h5 class="summary-title">অর্ডার সারসংক্ষেপ</h5>
                    <div class="d-flex justify-content-between summary-item">
                        <span><span id="summaryProductName">{{ $firstProduct->name ?? 'ফুল সেট' }}</span> × <span id="summaryQty">১</span></span>
                        <span>৳<span id="productTotal">{{ number_format($offerPrice, 0, '.', ',') }}</span></span>
                    </div>
                    <div class="d-flex justify-content-between summary-item border-bottom-dashed">
                        <span>ডেলিভারি চার্জ (<span id="summaryDeliveryText">ঢাকার বাইরে</span>)</span>
                        <span>৳<span id="deliveryCharge">{{ (int) $deliveryOutside }}</span></span>
                    </div>
                    <div class="d-flex justify-content-between summary-total-new">
                        <span>সর্বমোট</span>
                        <span class="total-amount">৳<span id="grandTotal">{{ number_format($offerPrice + $deliveryOutside, 0, '.', ',') }}</span></span>
                    </div>
                </div>

                <button type="submit" class="btn-action-primary w-100 mt-2">
                    <i class="fa-solid fa-cart-shopping"></i> অর্ডার কনফার্ম করুন
                </button>
                <div class="order-notice">
                    প্রিয় গ্রাহক, অনুগ্রহ করে ১০০ ভাগ নিশ্চিত হয়ে অর্ডার করতে অনুরোধ রইল। পার্সেল রিসিভ না করলে আমাদের
                    ডেলিভারি চার্জ, প্যাকেজিং সহ অনেক দিকে আর্থিক ক্ষতির সম্মুখীন হতে হয়।
                </div>
            </form>
        </div>

        <div class="footer-new">
            <img src="{{ asset('asset/images/logo.png') }}" alt="Dada Mart" class="footer-logo">
            <p class="footer-slogan">প্রিমিয়াম কোয়ালিটির নিত্য নতুন কালেকশন শপিং হবে এখন ঘরে বসেই</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('asset/script.js') }}"></script>
</body>

</html>
