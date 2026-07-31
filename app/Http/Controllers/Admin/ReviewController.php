<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['user', 'product'])
            ->when($request->status && $request->status !== 'all', fn($q) => $q->where('status', $request->status))
            ->when($request->rating, fn($q) => $q->where('rating', $request->rating))
            ->latest()
            ->paginate(12);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['status' => 'approved']);
        return back()->with('success', 'Review disetujui.');
    }

    public function reject(Review $review)
    {
        $review->update(['status' => 'rejected']);
        return back()->with('success', 'Review ditolak.');
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate(['reply' => 'required|string|max:1000']);
        $review->update(['seller_reply' => $request->reply, 'replied_at' => now()]);
        return back()->with('success', 'Balasan disimpan.');
    }
}
