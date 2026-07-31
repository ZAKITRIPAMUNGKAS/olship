<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string',
            'image'         => 'nullable|image|max:2048',
        ]);

        $orderItem = OrderItem::with('order')->findOrFail($request->order_item_id);

        // Security checks
        if ($orderItem->order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($orderItem->order->status !== 'completed') {
            return back()->with('error', 'Anda hanya dapat memberikan ulasan untuk pesanan yang sudah selesai.');
        }

        if ($orderItem->review_id) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        $review = Review::create([
            'user_id'    => Auth::id(),
            'product_id' => $orderItem->product_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
            'image_path' => $imagePath,
        ]);

        $orderItem->update(['review_id' => $review->id]);

        return back()->with('success', 'Terima kasih atas ulasan Anda!');
    }
}
