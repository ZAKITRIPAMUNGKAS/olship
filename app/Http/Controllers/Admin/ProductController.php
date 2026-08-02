<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use App\Exports\ProductsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['category', 'brand'])
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('sku', 'like', "%{$request->search}%"))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->status, fn($q) => match($request->status) {
                'active'   => $q->where('is_active', true),
                'inactive' => $q->where('is_active', false),
                'low'      => $q->where('stock', '<=', 5),
                default    => $q
            })
            ->latest()
            ->paginate(20);

        $categories = Category::active()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $brands     = Brand::all();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|unique:products',
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'nullable|exists:brands,id',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'images'      => 'nullable|array',
            'images.*'    => 'image|max:2048',
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        $product = Product::create($data);

        // [FIX BUG 4] Handling upload images saat pembuatan produk
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $img) {
                $path = $img->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0 // Gambar pertama jadi primary
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load('category', 'brand', 'reviews', 'images');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        $brands     = Brand::all();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'sku'         => 'required|string|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'brand_id'    => 'nullable|exists:brands,id',
            'price'       => 'required|numeric|min:0',
            'sale_price'  => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $product->update($data);

        // [FIX BUG 4] Storage Bloat: Hapus file lama saat gambar diupdate
        if ($request->hasFile('images')) {
            // Hapus file fisik dan record lama
            foreach ($product->images as $oldImage) {
                if (Storage::disk('public')->exists($oldImage->image_path)) {
                    Storage::disk('public')->delete($oldImage->image_path);
                }
            }
            $product->images()->delete();

            // Upload gambar baru
            foreach ($request->file('images') as $index => $img) {
                $path = $img->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // [FIX BUG 4] Hapus gambar dari storage saat produk dihapus
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk dihapus.');
    }

    public function bulk(Request $request)
    {
        $action = $request->input('action');
        $ids    = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with('error', 'Pilih produk terlebih dahulu.');
        }

        if ($action === 'delete') {
            $products = Product::whereIn('id', $ids)->get();
            foreach ($products as $product) {
                $this->destroy($product);
            }
        } else {
            match($action) {
                'activate' => Product::whereIn('id', $ids)->update(['is_active' => true]),
                'deactivate' => Product::whereIn('id', $ids)->update(['is_active' => false]),
                default    => null,
            };
        }

        return back()->with('success', 'Aksi massal berhasil.');
    }

    public function export()
    {
        // [FIX BUG 5] Export CSV menggunakan Maatwebsite Excel + FromQuery
        // Menghindari Out of Memory pada data besar
        return Excel::download(new ProductsExport, 'products_' . now()->format('Y-m-d') . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function toggleStatus(Product $product)
    {
        $product->update([
            'is_active' => !$product->is_active
        ]);

        return response()->json([
            'success'   => true,
            'is_active' => $product->is_active,
            'message'   => 'Status produk ' . ($product->is_active ? 'diaktifkan (ON)' : 'dinonaktifkan (OFF)')
        ]);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt']);
        // Import logic placeholder
        return back()->with('success', 'Import berhasil.');
    }
}
