<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WeeklyMenuController extends Controller
{
    public function index() { 
        return view('user.menus.index'); 
    }

    public function create() { 
        return view('user.menus.create'); 
    }

    public function store(Request $request) { 
        return back(); 
    }

    public function destroy($id) { 
        return back(); 
    }
}
