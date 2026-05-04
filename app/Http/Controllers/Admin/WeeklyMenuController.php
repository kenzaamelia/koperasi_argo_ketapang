<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WeeklyMenuController extends Controller
{
    public function index() { 
        return view('admin.menus.index'); 
    }

    public function show($id) { 
        return view('admin.menus.show'); 
    }
}
