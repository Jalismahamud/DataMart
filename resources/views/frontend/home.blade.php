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
        $productList = $products;
        $firstProduct = $productList->first();
        $firstSizes = $firstProduct && $firstProduct->sizes ? $firstProduct->sizes->pluck('name') : collect([]);
        $firstColors = $firstProduct && $firstProduct->colors ? $firstProduct->colors->pluck('name') : collect([]);
        $reviewItems = $reviews;

        $productTitle = $settings['product_title'] ?? 'ডিজিটাল প্রিন্ট বোরকা ও হিজাব সেট';
        $productSubtitle = $settings['product_subtitle'] ?? 'পর্দা ও স্টাইল এখন হবে একসাথে';
        $regularPrice = (float) ($settings['product_regular_price'] ?? ($firstProduct->regular_price ?? 3000));
        $offerPrice = (float) ($settings['product_offer_price'] ?? ($firstProduct ? $firstProduct->effective_price : 2550));
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
                <div class="carousel-inner" id="carousel-inner-container">
                    @if($firstProduct && $firstProduct->images && $firstProduct->images->count() > 0)
                        @foreach ($firstProduct->images as $index => $image)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <img src="{{ asset('images/products/' . $image->image_path) }}" class="d-block w-100 hero-img" alt="Product Image">
                            </div>
                        @endforeach
                    @else
                        <div class="carousel-item active">
                            <img src="{{ asset('asset/images/products/1.webp') }}" class="d-block w-100 hero-img" alt="Fallback Image">
                        </div>
                    @endif
                </div>
                <div class="carousel-indicators mt-3 mb-2" id="carousel-indicators-container" style="position: static;">
                    @if($firstProduct && $firstProduct->images && $firstProduct->images->count() > 0)
                        @foreach ($firstProduct->images as $index => $image)
                            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    @endif
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
            <h3 class="features-heading">পণ্য সম্পর্কে বিস্তারিত:</h3>
            <div id="dynamic-description" class="features-list-new" style="color: #666; font-size: 0.95rem; line-height: 1.6;">
                {!! $firstProduct->description ?? '<p>কোনো বিস্তারিত তথ্য নেই।</p>' !!}
            </div>
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
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form id="checkoutForm" action="{{ route('order.store') }}" method="POST">
                @csrf
                <div class="form-step">
                    <label class="step-label">① পণ্য বাছাই করুন</label>

                    @foreach ($productList as $index => $product)
                        @php
                            $productPrice = (float) (($product->discount_price > 0) ? $product->discount_price : $product->regular_price);
                            if ($productPrice == 0) $productPrice = $product->base_price ?? 2550;
                            $productDescription = $product->short_description ?? 'Premium product';
                            $productSizes = $product->sizes ? $product->sizes->pluck('name')->toJson() : '[]';
                            $productColors = $product->colors ? $product->colors->pluck('name')->toJson() : '[]';
                            $productImages = $product->images ? $product->images->map(function($img) { return asset('images/products/' . $img->image_path); })->toJson() : '[]';
                        @endphp
                        <label class="product-card {{ $index === 0 ? 'selected' : '' }}" data-sizes="{{ $productSizes }}" data-colors="{{ $productColors }}" data-images="{{ $productImages }}" data-description="{{ $product->description }}" onclick="selectProduct(this, {{ $productPrice }})">
                            @if ($index === 0)
                                <div class="popular-badge">সর্বাধিক জনপ্রিয়</div>
                            @endif
                            <input type="radio" name="product_id" value="{{ $product->id ?? 'product-' . $index }}" {{ $index === 0 ? 'checked' : '' }} class="d-none">
                            <div class="d-flex align-items-center w-100">
                                <div class="product-icon">⭐</div>
                                <div class="product-details">
                                    <h5>{{ $product->name ?? 'ফুল সেট বোরকা' }}</h5>
                                    <p>{{ \Illuminate\Support\Str::limit($productDescription, 70) }}</p>
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
                    <div class="size-grid-5" id="color-container">
                        @foreach ($firstColors as $index => $color)
                            <label class="size-box {{ $index === 0 ? 'active' : '' }}" onclick="selectColor(this)">
                                <input type="radio" name="selected_color" value="{{ $color }}" {{ $index === 0 ? 'checked' : '' }} class="d-none">
                                {{ $color }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-step">
                    <label class="step-label">③ সাইজ বাছাই করুন <span class="step-sub">(লং সাইজ ইঞ্চিতে)</span></label>
                    <div class="size-grid-5" id="size-container">
                        @foreach ($firstSizes as $index => $size)
                            <label class="size-box {{ $index === 0 ? 'active' : '' }}" onclick="selectSize(this)">
                                <input type="radio" name="selected_size" value="{{ $size }}" {{ $index === 0 ? 'checked' : '' }} class="d-none">
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
                            <input type="hidden" name="quantity" id="qty_hidden" value="1">
                            <button type="button" class="qty-btn plus" onclick="increaseQty()">+</button>
                        </div>
                        <div class="qty-total-text">
                            মোট: <span id="qtyTotal">৳{{ number_format($offerPrice, 0, '.', ',') }}</span>
                        </div>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">আপনার নাম</label>
                    <input type="text" name="customer_name" class="form-control-custom" placeholder="আপনার নাম লিখুন..." required>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">মোবাইল নাম্বার</label>
                    <input type="tel" name="phone" class="form-control-custom" placeholder="01XXXXXXXXX" required>
                </div>

                <div class="form-group-custom">
                    <label class="form-label-custom">ডেলিভারি ঠিকানা</label>
                    <textarea name="address" class="form-control-custom" rows="3" placeholder="আপনার সম্পূর্ণ ঠিকানা লিখুন..." required></textarea>
                </div>

                <div class="form-step">
                    <label class="step-label">⑤ ডেলিভারি এলাকা</label>
                    <label class="product-card selected delivery-new-card" onclick="selectDelivery(this, {{ $deliveryOutside }}, 'ঢাকার বাইরে')">
                        <input type="radio" name="city" class="d-none" value="Outside Dhaka" checked>
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
                        <input type="radio" name="city" class="d-none" value="Dhaka">
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
