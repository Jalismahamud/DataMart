<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use File;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::latest()->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('admin.reviews.form', ['review' => new Review()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $uploadDir = public_path('images/reviews');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $fileName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $fileName);
            $imagePath = $fileName;
        }

        Review::create([
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'Review image uploaded successfully.');
    }

    public function edit(Review $review)
    {
        return view('admin.reviews.form', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $imagePath = $review->image;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($imagePath) {
                $oldPath = public_path('images/reviews/' . $imagePath);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Upload new image
            $uploadDir = public_path('images/reviews');
            if (!File::exists($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }
            $fileName = time() . '_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $fileName);
            $imagePath = $fileName;
        }

        $review->update([
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.reviews.index')->with('success', 'Review image updated successfully.');
    }

    public function destroy(Review $review)
    {
        if ($review->image) {
            $imagePath = public_path('images/reviews/' . $review->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $review->delete();

        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}
