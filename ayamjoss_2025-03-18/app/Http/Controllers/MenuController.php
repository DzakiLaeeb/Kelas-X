<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kategori')->get();
        $kategoris = Kategori::all();
        return view('menu', ['menus' => $menus, 'kategoris' => $kategoris]);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        
        $menus = Menu::with('kategori')
                     ->where('nama', 'LIKE', "%{$search}%")
                     ->orWhere('deskripsi', 'LIKE', "%{$search}%")
                     ->orWhere('harga', 'LIKE', "%{$search}%")
                     ->get();

        return view('menu', [
            'menus' => $menus,
            'search' => $search,
            'kategoris' => Kategori::all()
        ]);
    }

    public function show($idmenu)
    {
        $menu = Menu::with('kategori')->findOrFail($idmenu);
        return view('menu.detail', ['menu' => $menu]);
    }
}


