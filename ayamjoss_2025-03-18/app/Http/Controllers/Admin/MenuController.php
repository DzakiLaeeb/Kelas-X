<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kategori')->get();
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        $kategoris = Kategori::all();
        return view('admin.menu.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'gambar' => 'required|url',
            'badge' => 'nullable',
            'badge_type' => 'nullable'
        ]);

        Menu::create($request->all());
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit($idmenu)
    {
        $menu = Menu::findOrFail($idmenu);
        $kategoris = Kategori::all();
        return view('admin.menu.edit', compact('menu', 'kategoris'));
    }

    public function update(Request $request, $idmenu)
    {
        $menu = Menu::findOrFail($idmenu);
        
        $request->validate([
            'nama' => 'required',
            'kategori_id' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|numeric',
            'gambar' => 'required|url',
            'badge' => 'nullable',
            'badge_type' => 'nullable'
        ]);

        $menu->update($request->all());
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diupdate');
    }

    public function destroy(Menu $menu)
    {
        try {
            $menu->delete();
            return redirect()
                ->route('admin.menu.index')
                ->with('success', 'Menu berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.menu.index')
                ->with('error', 'Gagal menghapus menu');
        }
    }
}



