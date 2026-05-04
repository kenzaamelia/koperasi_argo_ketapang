<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products   = Product::with('category')->latest()->get();
        $categories = Category::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk'  => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'deskripsi'    => 'nullable|string',
            'harga'        => 'required|numeric|min:0',
            'satuan'       => 'required|string|max:50',
            'stok'         => 'required|integer|min:0',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_available' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($request->nama_produk . '-' . time());

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('products', 'public');
        }

        $validated['is_available'] = $request->has('is_available');

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nama_produk'  => 'required|string|max:255',
            'category_id'  => 'required|exists:categories,id',
            'deskripsi'    => 'nullable|string',
            'harga'        => 'required|numeric|min:0',
            'satuan'       => 'required|string|max:50',
            'stok'         => 'required|integer|min:0',
            'foto'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_available' => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($product->foto) {
                \Storage::disk('public')->delete($product->foto);
            }
            $validated['foto'] = $request->file('foto')->store('products', 'public');
        }

        $validated['is_available'] = $request->has('is_available');

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        if ($product->foto) {
            \Storage::disk('public')->delete($product->foto);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}