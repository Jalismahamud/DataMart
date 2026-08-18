<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Tests\TestCase;

class HomePageDynamicTest extends TestCase
{
    public function test_home_page_uses_dynamic_site_settings(): void
    {
        SiteSetting::query()->delete();

        SiteSetting::create(['key' => 'hero_title', 'value' => 'ডাইনামিক হিরো টাইটেল']);
        SiteSetting::create(['key' => 'hero_subtitle', 'value' => 'ডাইনামিক সাবটাইটেল']);
        SiteSetting::create(['key' => 'product_title', 'value' => 'ডাইনামিক প্রোডাক্ট টাইটেল']);
        SiteSetting::create(['key' => 'review_title', 'value' => 'ডাইনামিক রিভিউ']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('ডাইনামিক হিরো টাইটেল');
        $response->assertSee('ডাইনামিক প্রোডাক্ট টাইটেল');
        $response->assertSee('ডাইনামিক রিভিউ');
    }
}
