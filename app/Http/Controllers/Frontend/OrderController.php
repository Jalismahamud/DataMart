<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'product_id' => ['required', 'exists:products,id'],
            'variation_id' => ['required', 'exists:product_variations,id'],
            'selected_size' => ['nullable', 'string', 'max:50'],
            'selected_color' => ['nullable', 'string', 'max:50'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $variation = ProductVariation::findOrFail($validated['variation_id']);

        if ($variation->product_id !== $product->id) {
            abort(422, 'Selected variation does not belong to the product.');
        }

        $insideDhaka = (float) SiteSetting::getValue('delivery_inside_dhaka', 70);
        $outsideDhaka = (float) SiteSetting::getValue('delivery_outside_dhaka', 120);

        $shippingCost = $validated['city'] === 'Dhaka' ? $insideDhaka : $outsideDhaka;
        $unitPrice = (float) ($variation->discount_price > 0 && $variation->discount_price < $variation->regular_price
            ? $variation->discount_price
            : ($variation->regular_price ?? $variation->price));
        $total = ($unitPrice * $validated['quantity']) + $shippingCost;

        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'delivery_zone' => $validated['city'] === 'Dhaka' ? 'inside_dhaka' : 'outside_dhaka',
            'product_id' => $product->id,
            'variation_id' => $variation->id,
            'selected_size' => $validated['selected_size'] ?? $variation->size,
            'selected_color' => $validated['selected_color'] ?? $variation->color,
            'quantity' => $validated['quantity'],
            'unit_price' => $unitPrice,
            'shipping_cost' => $shippingCost,
            'total_amount' => $total,
            'payment_method' => 'cash_on_delivery',
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'Order placed successfully.',
                'invoice_number' => $order->invoice_number,
                'order_id' => $order->id,
            ], 201);
        }

        return redirect()->back()->with('success', 'Order placed successfully.');
    }
}
