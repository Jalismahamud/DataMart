<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['variations', 'images'])->latest()->get();

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
            'regular_price' => ['nullable', 'numeric'],
            'discount_price' => ['nullable', 'numeric'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'variations' => ['nullable', 'array'],
            'variations.*.name' => ['nullable', 'string'],
            'variations.*.price' => ['nullable', 'numeric'],
            'variations.*.regular_price' => ['nullable', 'numeric'],
            'variations.*.discount_price' => ['nullable', 'numeric'],
            'variations.*.size' => ['nullable', 'string', 'max:50'],
            'variations.*.color' => ['nullable', 'string', 'max:50'],
            'variations.*.is_default' => ['nullable', 'boolean'],
        ]);

        $regularPrice = $validated['regular_price'] ?? $validated['base_price'] ?? 0;
        $discountPrice = $validated['discount_price'] ?? null;

        $product = Product::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'base_price' => $regularPrice,
            'regular_price' => $regularPrice,
            'discount_price' => $discountPrice,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        $this->storeImages($request, $product);

        $variationRecords = $this->normalizeVariations($validated['variations'] ?? [], $regularPrice);
        $selectedDefaultId = null;

        foreach ($variationRecords as $variation) {
            $created = $product->variations()->create([
                'name' => $variation['name'],
                'price' => $variation['price'],
                'regular_price' => $variation['regular_price'],
                'discount_price' => $variation['discount_price'],
                'size' => $variation['size'],
                'color' => $variation['color'],
                'is_default' => false,
            ]);

            if ($variation['is_default']) {
                $selectedDefaultId = $created->id;
            }
        }

        $product->syncDefaultVariation($selectedDefaultId);

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
            'regular_price' => ['nullable', 'numeric'],
            'discount_price' => ['nullable', 'numeric'],
            'is_active' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'variations' => ['nullable', 'array'],
            'variations.*.name' => ['nullable', 'string'],
            'variations.*.price' => ['nullable', 'numeric'],
            'variations.*.regular_price' => ['nullable', 'numeric'],
            'variations.*.discount_price' => ['nullable', 'numeric'],
            'variations.*.size' => ['nullable', 'string', 'max:50'],
            'variations.*.color' => ['nullable', 'string', 'max:50'],
            'variations.*.is_default' => ['nullable', 'boolean'],
        ]);

        $regularPrice = $validated['regular_price'] ?? $validated['base_price'] ?? 0;
        $discountPrice = $validated['discount_price'] ?? null;

        $product->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'base_price' => $regularPrice,
            'regular_price' => $regularPrice,
            'discount_price' => $discountPrice,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        if ($request->hasFile('images')) {
            foreach ($product->images as $image) {
                $imagePath = public_path('images/products/' . $image->image_path);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            $product->images()->delete();
        }

        $this->storeImages($request, $product);

        $variationRecords = $this->normalizeVariations($validated['variations'] ?? [], $regularPrice);
        $selectedDefaultId = null;

        $product->variations()->delete();

        foreach ($variationRecords as $variation) {
            $created = $product->variations()->create([
                'name' => $variation['name'],
                'price' => $variation['price'],
                'regular_price' => $variation['regular_price'],
                'discount_price' => $variation['discount_price'],
                'size' => $variation['size'],
                'color' => $variation['color'],
                'is_default' => false,
            ]);

            if ($variation['is_default']) {
                $selectedDefaultId = $created->id;
            }
        }

        $product->syncDefaultVariation($selectedDefaultId);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            $imagePath = public_path('images/products/' . $image->image_path);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    protected function normalizeVariations(array $variations, float $fallbackPrice): array
    {
        $filtered = [];

        foreach ($variations as $variation) {
            $name = trim((string) ($variation['name'] ?? ''));
            $size = trim((string) ($variation['size'] ?? ''));
            $color = trim((string) ($variation['color'] ?? ''));
            $price = (float) ($variation['price'] ?? $fallbackPrice);
            $regularPrice = (float) ($variation['regular_price'] ?? $price);
            $discountPrice = $variation['discount_price'] ?? null;
            $isDefault = !empty($variation['is_default']);

            if ($name === '' && $size === '' && $color === '' && $price <= 0) {
                continue;
            }

            $filtered[] = [
                'name' => $name !== '' ? $name : ($size !== '' || $color !== '' ? trim($size . ' ' . $color) : 'Variation'),
                'price' => $price,
                'regular_price' => $regularPrice,
                'discount_price' => $discountPrice !== null && $discountPrice !== '' ? (float) $discountPrice : null,
                'size' => $size !== '' ? $size : null,
                'color' => $color !== '' ? $color : null,
                'is_default' => $isDefault,
            ];
        }

        if (empty($filtered)) {
            return [[
                'name' => 'Default',
                'price' => $fallbackPrice,
                'regular_price' => $fallbackPrice,
                'discount_price' => null,
                'size' => null,
                'color' => null,
                'is_default' => true,
            ]];
        }

        $defaultIndex = null;
        foreach ($filtered as $index => $variation) {
            if ($variation['is_default']) {
                $defaultIndex = $index;
                break;
            }
        }

        if ($defaultIndex === null) {
            $filtered[0]['is_default'] = true;
        } else {
            foreach ($filtered as $index => $variation) {
                $filtered[$index]['is_default'] = $index === $defaultIndex;
            }
        }

        return $filtered;
    }

    protected function storeImages(Request $request, Product $product): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $uploadDir = public_path('images/products');
        if (!File::exists($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }

        foreach ($request->file('images') as $index => $file) {
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);

            $product->images()->create([
                'image_path' => $fileName,
                'is_featured' => $index === 0,
                'sort_order' => $index,
            ]);
        }
    }
}
