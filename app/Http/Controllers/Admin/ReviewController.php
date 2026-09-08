<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'car'])->latest()->get();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggleFeatured(Request $request, Review $review)
    {
        $review->update([
            'is_featured' => !$review->is_featured
        ]);

        return back()->with('success', 'Status ulasan berhasil diubah!');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Ulasan berhasil dihapus!');
    }
}
