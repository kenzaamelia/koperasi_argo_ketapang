<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WeeklyMenu;

class SppgController extends Controller
{
    public function index()
    {
        $sppgList = User::where('role', 'user')
            ->with('sppgProfile')
            ->latest()
            ->get();

        return view('admin.sppg.index', compact('sppgList'));
    }

    public function show($id)
    {
        $sppg = User::where('role', 'user')
            ->with(['sppgProfile', 'weeklyMenus'])
            ->findOrFail($id);

        $totalMenu = $sppg->weeklyMenus->count();

        $menuTerbaru = $sppg->weeklyMenus()
            ->latest()
            ->take(6)
            ->get();

        return view('admin.sppg.show', compact('sppg', 'totalMenu', 'menuTerbaru'));
    }
}