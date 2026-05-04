<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\WeeklyMenu;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSppg     = User::where('role', 'user')->count();
        $totalProduk   = Product::count();
        $totalMenuBaru = WeeklyMenu::whereDate('created_at', today())->count();

        return view('admin.dashboard', compact(
            'totalSppg',
            'totalProduk',
            'totalMenuBaru'
        ));
    }
}