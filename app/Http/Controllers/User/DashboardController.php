<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\WeeklyMenu;

class DashboardController extends Controller
{
    public function index()
    {
        $produkTerbaru = Product::where('is_available', true)
            ->latest()
            ->take(6)
            ->get();

        $menuSaya = WeeklyMenu::where('user_id', auth()->id())
            ->latest()
            ->take(3)
            ->get();

        return view('user.dashboard', compact('produkTerbaru', 'menuSaya'));
    }
}