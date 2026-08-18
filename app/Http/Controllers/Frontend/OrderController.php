<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
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
            'selected_size' => ['nullable', 'string', 'max:50'],
            'selected_color' => ['nullable', 'string', 'max:50'],
            'quantity' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        $insideDhaka = (float) SiteSetting::getValue('delivery_inside_dhaka', 70);
        $outsideDhaka = (float) SiteSetting::getValue('delivery_outside_dhaka', 120);

        $shippingCost = $validated['city'] === 'Dhaka' ? $insideDhaka : $outsideDhaka;
        $unitPrice = $product->effective_price;
        $total = ($unitPrice * $validated['quantity']) + $shippingCost;

        Order::create([
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'delivery_zone' => $validated['city'] === 'Dhaka' ? 'inside_dhaka' : 'outside_dhaka',
            'product_id' => $product->id,
            'selected_size' => $validated['selected_size'] ?? null,
            'selected_color' => $validated['selected_color'] ?? null,
            'quantity' => $validated['quantity'],
            'unit_price' => $unitPrice,
            'shipping_cost' => $shippingCost,
            'total_amount' => $total,
            'payment_method' => 'cash_on_delivery',
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Order placed successfully.');
    }
}
