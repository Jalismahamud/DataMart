<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductVariationManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_variations_store_size_color_and_only_keep_one_default(): void
    {
        $product = Product::create([
            'name' => 'Premium Borka',
            'slug' => 'premium-borka',
            'regular_price' => 2000,
            'discount_price' => 1800,
            'is_active' => true,
        ]);

        $product->sizes()->create(['size' => 'M']);
        $product->sizes()->create(['size' => 'L']);
        $product->colors()->create(['color' => 'Black']);
        $product->colors()->create(['color' => 'White']);

        $product->variations()->create([
            'name' => 'Full Set',
            'short_description' => 'Premium full coverage set',
            'price' => 1800,
            'regular_price' => 2000,
            'discount_price' => 1800,
            'is_default' => true,
        ]);

        $product->variations()->create([
            'name' => 'Without Hijab',
            'short_description' => 'Modern design without hijab',
            'price' => 1600,
            'regular_price' => 1800,
            'discount_price' => 1600,
            'is_default' => false,
        ]);

        $this->assertDatabaseHas('product_sizes', [
            'product_id' => $product->id,
            'size' => 'M',
        ]);

        $this->assertDatabaseHas('product_colors', [
            'product_id' => $product->id,
            'color' => 'Black',
        ]);

        $this->assertSame(2, $product->fresh()->variations()->count());
        $this->assertSame(1, $product->fresh()->variations()->where('is_default', true)->count());
    }

    public function test_only_one_product_record_can_exist(): void
    {
        Product::create([
            'name' => 'Premium Borka',
            'slug' => 'premium-borka',
            'regular_price' => 2000,
            'discount_price' => 1800,
            'is_active' => true,
        ]);

        $this->expectException(\RuntimeException::class);

        Product::create([
            'name' => 'Premium Borka 2',
            'slug' => 'premium-borka-2',
            'regular_price' => 2200,
            'discount_price' => 1900,
            'is_active' => true,
        ]);
    }
}
