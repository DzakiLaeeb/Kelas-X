<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function menu()
    {
        $kategoris = Kategori::all();
        $menus = Menu::paginate(3);
        
        return view('menu', [
            'kategoris' => $kategoris,
            'menus' => $menus
        ]);
    }

    public function kontak()
    {
        return view('kontak');
    }
}



