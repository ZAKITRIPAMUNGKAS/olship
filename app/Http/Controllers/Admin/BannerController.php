<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'link'       => 'nullable|url',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer',
            'image'      => 'required|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $data['is_active']  = $request->boolean('is_active', true);
        $data['sort_order'] = $request->input('sort_order', Banner::max('sort_order') + 1);

        Banner::create($data);
        return redirect()->route('admin.banners.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $request->validate([
            'title'      => 'required|string|max:255',
            'link'       => 'nullable|url',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer',
            'image'      => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image) Storage::disk('public')->delete($banner->image);
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner diperbarui.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image) Storage::disk('public')->delete($banner->image);
        $banner->delete();
        return redirect()->route('admin.banners.index')->with('success', 'Banner dihapus.');
    }

    public function show(Banner $banner)
    {
        return redirect()->route('admin.banners.edit', $banner);
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);
        foreach ($request->order as $id => $sort) {
            Banner::where('id', $id)->update(['sort_order' => $sort]);
        }
        return response()->json(['ok' => true]);
    }

    public function toggle(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);
        return back()->with('success', 'Status banner diperbarui.');
    }
}
