<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent', 'children')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->orderBy('name')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'icon_class'  => 'nullable|string|max:50',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function show(Category $category)
    {
        return redirect()->route('admin.categories.edit', $category);
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'parent_id'   => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'icon_class'  => 'nullable|string|max:50',
            'sort_order'  => 'nullable|integer',
            'is_active'   => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count()) {
            return back()->with('error', 'Kategori masih memiliki produk, tidak bisa dihapus.');
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori dihapus.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['order' => 'required|array']);
        foreach ($request->order as $id => $sort) {
            Category::where('id', $id)->update(['sort_order' => $sort]);
        }
        return response()->json(['ok' => true]);
    }
}
