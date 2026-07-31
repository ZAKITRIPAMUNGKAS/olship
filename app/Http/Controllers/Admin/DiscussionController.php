<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductDiscussion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    public function index()
    {
        $discussions = ProductDiscussion::whereNull('parent_id')
            ->with(['user', 'product', 'replies.user'])
            ->latest()
            ->paginate(15);
            
        return view('admin.discussions.index', compact('discussions'));
    }

    public function reply(Request $request, ProductDiscussion $discussion)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        ProductDiscussion::create([
            'user_id' => Auth::id(),
            'product_id' => $discussion->product_id,
            'parent_id' => $discussion->id,
            'message' => $request->message,
            'is_admin_reply' => true
        ]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function destroy(ProductDiscussion $discussion)
    {
        $discussion->delete();
        return back()->with('success', 'Diskusi berhasil dihapus.');
    }
}
