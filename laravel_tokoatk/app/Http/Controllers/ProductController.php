<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function show($id)
    {
        try {
            $product = DB::table('products')
                ->select(
                    'products.id',
                    'products.gambar',
                    'products.nama_produk as name',
                    'products.harga as price',
                    'products.stok as stock',
                    'products.deskripsi as description',
                    'categories.name as category_name'
                )
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->where('products.id', $id)
                ->first();

            if (!$product) {
                return redirect()->route('shop')->with('error', 'Produk tidak ditemukan');
            }

            return view('product_detail', compact('product'));

        } catch (\Exception $e) {
            return redirect()->route('shop')->with('error', 'Error saat memuat produk');
        }
    }
}


