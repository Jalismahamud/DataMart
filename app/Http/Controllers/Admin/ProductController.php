<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('variations')->latest()->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.form', ['product' => new Product()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_price' => ['nullable', 'numeric'],
            'featured_image' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'variations' => ['nullable', 'array'],
            'variations.*.name' => ['required_with:variations', 'string'],
            'variations.*.price' => ['required_with:variations', 'numeric'],
            'variations.*.is_default' => ['nullable', 'boolean'],
        ]);

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'base_price' => $validated['base_price'] ?? 0,
            'featured_image' => $validated['featured_image'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        foreach ($validated['variations'] ?? [] as $variation) {
            $product->variations()->create([
                'name' => $variation['name'],
                'price' => $variation['price'],
                'is_default' => $variation['is_default'] ?? false,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_price' => ['nullable', 'numeric'],
            'featured_image' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'variations' => ['nullable', 'array'],
            'variations.*.name' => ['required_with:variations', 'string'],
            'variations.*.price' => ['required_with:variations', 'numeric'],
            'variations.*.is_default' => ['nullable', 'boolean'],
        ]);

        $product->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'base_price' => $validated['base_price'] ?? 0,
            'featured_image' => $validated['featured_image'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        $product->variations()->delete();

        foreach ($validated['variations'] ?? [] as $variation) {
            $product->variations()->create([
                'name' => $variation['name'],
                'price' => $variation['price'],
                'is_default' => $variation['is_default'] ?? false,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
