<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['sizes', 'colors', 'images'])->latest()->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $product = Product::first();

        if ($product) {
            return redirect()->route('admin.products.edit', $product);
        }

        return view('admin.products.form', ['product' => new Product]);
    }

    public function store(Request $request)
    {
        if (Product::exists()) {
            return redirect()->route('admin.products.edit', Product::first())->with('warning', 'Only one product is allowed. Update the existing product instead.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_price' => ['nullable', 'numeric'],
            'regular_price' => ['nullable', 'numeric'],
            'discount_price' => ['nullable', 'numeric'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'sizes' => ['nullable', 'array'],
            'sizes.*.name' => ['nullable', 'string', 'max:50'],
            'colors' => ['nullable', 'array'],
            'colors.*.name' => ['nullable', 'string', 'max:50'],
        ]);

        $regularPrice = $validated['regular_price'] ?? $validated['base_price'] ?? 0;
        $discountPrice = $validated['discount_price'] ?? null;

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'base_price' => $regularPrice,
            'regular_price' => $regularPrice,
            'discount_price' => $discountPrice,
            'is_active' => true,
        ]);

        $this->storeImages($request, $product);
        $this->syncSizesAndColors($product, $validated);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load(['sizes', 'colors']);

        return view('admin.products.form', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'base_price' => ['nullable', 'numeric'],
            'regular_price' => ['nullable', 'numeric'],
            'discount_price' => ['nullable', 'numeric'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'sizes' => ['nullable', 'array'],
            'sizes.*.name' => ['nullable', 'string', 'max:50'],
            'colors' => ['nullable', 'array'],
            'colors.*.name' => ['nullable', 'string', 'max:50'],
        ]);

        $regularPrice = $validated['regular_price'] ?? $validated['base_price'] ?? 0;
        $discountPrice = $validated['discount_price'] ?? null;

        $product->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
            'base_price' => $regularPrice,
            'regular_price' => $regularPrice,
            'discount_price' => $discountPrice,
            'is_active' => true,
        ]);

        if ($request->hasFile('images')) {
            foreach ($product->images as $image) {
                $imagePath = public_path('images/products/'.$image->image_path);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
            $product->images()->delete();
        }

        $this->storeImages($request, $product);
        $this->syncSizesAndColors($product, $validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            $imagePath = public_path('images/products/'.$image->image_path);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    protected function syncSizesAndColors(Product $product, array $validated): void
    {
        $product->sizes()->delete();
        $product->colors()->delete();

        if (! empty($validated['sizes'])) {
            foreach ($validated['sizes'] as $size) {
                if (! empty(trim($size['name'] ?? ''))) {
                    $product->sizes()->create(['name' => trim($size['name'])]);
                }
            }
        }

        if (! empty($validated['colors'])) {
            foreach ($validated['colors'] as $color) {
                if (! empty(trim($color['name'] ?? ''))) {
                    $product->colors()->create(['name' => trim($color['name'])]);
                }
            }
        }
    }

    protected function storeImages(Request $request, Product $product): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $uploadDir = public_path('images/products');
        if (! File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        foreach ($request->file('images') as $index => $file) {
            $fileName = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);

            $product->images()->create([
                'image_path' => $fileName,
                'is_featured' => $index === 0,
                'sort_order' => $index,
            ]);
        }
    }
}
