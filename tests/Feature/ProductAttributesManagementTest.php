<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAttributesManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_store_multiple_sizes_and_colors(): void
    {
        $product = Product::create([
            'name' => 'Premium Borka',
            'slug' => 'premium-borka',
            'regular_price' => 2000,
            'discount_price' => 1800,
            'is_active' => true,
        ]);

        $product->sizes()->create(['name' => 'M']);
        $product->sizes()->create(['name' => 'L']);
        
        $product->colors()->create(['name' => 'Black']);
        $product->colors()->create(['name' => 'White']);

        $this->assertDatabaseHas('product_sizes', [
            'product_id' => $product->id,
            'name' => 'M',
        ]);
        $this->assertDatabaseHas('product_sizes', [
            'product_id' => $product->id,
            'name' => 'L',
        ]);

        $this->assertDatabaseHas('product_colors', [
            'product_id' => $product->id,
            'name' => 'Black',
        ]);
        $this->assertDatabaseHas('product_colors', [
            'product_id' => $product->id,
            'name' => 'White',
        ]);
        
        $this->assertSame(2, $product->fresh()->sizes()->count());
        $this->assertSame(2, $product->fresh()->colors()->count());
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
