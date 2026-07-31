<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('items.product')->latest()->take(5)->get();
        $wishlistCount = \App\Models\Wishlist::where('user_id', $user->id)->count();
        
        return view('storefront.dashboard.index', [
            'user' => $user,
            'orders' => $orders,
            'wishlistCount' => $wishlistCount,
        ]);
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->with('items.product')->latest()->paginate(10);
        return view('storefront.dashboard.orders.index', compact('orders'));
    }

    public function orderDetail($orderNumber)
    {
        $order = Auth::user()->orders()
            ->with(['items.product'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();
            
        return view('storefront.dashboard.orders.show', compact('order'));
    }

    public function addresses()
    {
        $addresses = Auth::user()->addresses()->with(['province', 'city'])->get();
        return view('storefront.dashboard.addresses.index', compact('addresses'));
    }

    public function profile()
    {
        return view('storefront.dashboard.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        auth()->user()->unreadNotifications->markAsRead();
        
        return view('storefront.dashboard.notifications.index', compact('notifications'));
    }
}
