<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch banners from the database
        $banners = DB::table('banners')
            ->where('is_active', 1)
            ->orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch featured products
        $featuredProducts = DB::table('products')
            ->select(
                'products.id',
                'products.gambar as image_url',
                'products.nama_produk as name',
                'products.harga as price',
                'products.stok as stock',
                'categories.name as category_name'
            )
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->orderBy('products.created_at', 'desc')
            ->limit(4)
            ->get();

        return view('index', [
            'banners' => $banners,
            'featuredProducts' => $featuredProducts
        ]);
    }
}
