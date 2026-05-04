<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SppgController extends Controller
{
    public function index() { 
        return view('admin.sppg.index'); 
    }

    public function show($id) { 
        return view('admin.sppg.show'); 
        }
}
