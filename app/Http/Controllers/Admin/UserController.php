<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(20);

        $userStats = [
            'active' => User::where('is_active', true)->count(),
            'today'  => User::whereDate('created_at', today())->count(),
        ];

        return view('admin.users.index', compact('users', 'userStats'));
    }

    public function show(User $user)
    {
        $user->load('roles', 'orders');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'is_active' => 'boolean',
        ]);

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Data pengguna diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna dihapus.');
    }

    public function ban(User $user)
    {
        $user->update(['is_active' => false]);
        return back()->with('success', "Pengguna {$user->name} dinonaktifkan.");
    }

    public function unban(User $user)
    {
        $user->update(['is_active' => true]);
        return back()->with('success', "Pengguna {$user->name} diaktifkan kembali.");
    }
}
