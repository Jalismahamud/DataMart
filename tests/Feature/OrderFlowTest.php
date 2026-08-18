<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_submit_order_with_selected_size_and_color(): void
    {
        $product = Product::create([
            'name' => 'Premium Borka',
            'slug' => 'premium-borka',
            'short_description' => 'Premium quality',
            'regular_price' => 2000,
            'discount_price' => 1800,
            'is_active' => true,
        ]);

        $product->sizes()->create(['name' => 'M']);
        $product->colors()->create(['name' => 'Black']);

        $response = $this->post('/order', [
            'customer_name' => 'Rafi Ahmed',
            'phone' => '01700000000',
            'address' => 'Dhanmondi, Dhaka',
            'city' => 'Dhaka',
            'product_id' => $product->id,
            'selected_size' => 'M',
            'selected_color' => 'Black',
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'product_id' => $product->id,
            'selected_size' => 'M',
            'selected_color' => 'Black',
        ]);
    }
}
