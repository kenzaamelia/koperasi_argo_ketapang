<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeeklyMenu;
use App\Models\User;

class WeeklyMenuController extends Controller
{
    public function index()
    {
        $menus = WeeklyMenu::with(['user.sppgProfile'])
            ->latest()
            ->get();

        $sppgList = User::where('role', 'user')
            ->with('sppgProfile')
            ->get();

        return view('admin.menus.index', compact('menus', 'sppgList'));
    }

    public function show($id)
    {
        $menu = WeeklyMenu::with(['user.sppgProfile'])->findOrFail($id);
        return view('admin.menus.show', compact('menu'));
    }
}