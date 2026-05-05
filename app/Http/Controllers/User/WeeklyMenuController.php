<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WeeklyMenu;
use Illuminate\Http\Request;

class WeeklyMenuController extends Controller
{
    public function index()
    {
        $menus = WeeklyMenu::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('user.menus.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'           => 'nullable|string|max:255',
            'foto'            => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan'      => 'nullable|string',
        ], [
            'foto.required'            => 'Foto menu wajib diupload.',
            'foto.image'               => 'File harus berupa gambar.',
            'foto.max'                 => 'Ukuran foto maksimal 5MB.',
            'tanggal_mulai.required'   => 'Tanggal mulai wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus setelah tanggal mulai.',
        ]);

        $validated['foto']    = $request->file('foto')->store('menus', 'public');
        $validated['user_id'] = auth()->id();

        WeeklyMenu::create($validated);

        return redirect()->route('user.menus.index')
            ->with('success', 'Menu mingguan berhasil diupload!');
    }

    public function destroy($id)
    {
        $menu = WeeklyMenu::where('user_id', auth()->id())
            ->findOrFail($id);

        if ($menu->foto) {
            \Storage::disk('public')->delete($menu->foto);
        }

        $menu->delete();

        return redirect()->route('user.menus.index')
            ->with('success', 'Menu berhasil dihapus.');
    }
}