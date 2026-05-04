<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class CatalogController extends Controller
{
    public function index()
    {
        $products   = Product::with('category')
            ->where('is_available', true)
            ->latest()
            ->get();
        $categories = Category::whereHas('products', function($q) {
            $q->where('is_available', true);
        })->get();

        return view('user.catalog.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::with('category')
            ->where('is_available', true)
            ->findOrFail($id);

        return view('user.catalog.show', compact('product'));
    }
}