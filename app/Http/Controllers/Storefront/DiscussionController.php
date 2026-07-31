<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\ProductDiscussion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'message'   => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:product_discussions,id',
        ]);

        ProductDiscussion::create([
            'user_id'    => Auth::id(),
            'product_id' => $product->id,
            'parent_id'  => $request->parent_id,
            'message'    => $request->message,
            'is_admin_reply' => Auth::user()->hasAnyRole(['admin', 'staff']),
        ]);

        return back()->with('success', 'Pertanyaan Anda telah dikirim.');
    }
}
