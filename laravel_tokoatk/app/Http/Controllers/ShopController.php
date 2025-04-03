<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('products')
            ->select(
                'products.id',
                'products.gambar',
                'products.nama_produk as name',
                'products.harga as price',
                'products.stok as stock',
                'categories.name as category_name'
            )
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id');

        // Handle search query
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('products.nama_produk', 'like', '%' . $searchTerm . '%')
                  ->orWhere('products.deskripsi', 'like', '%' . $searchTerm . '%')
                  ->orWhere('categories.name', 'like', '%' . $searchTerm . '%');
            });
        }

        // Handle sorting
        $sort = $request->input('sort', 'newest');

        switch ($sort) {
            case 'price_low':
                $query->orderBy('products.harga', 'asc');
                break;
            case 'price_high':
                $query->orderBy('products.harga', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('products.nama_produk', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('products.nama_produk', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('products.created_at', 'desc');
                break;
        }

        $products = $query->paginate(16);

        // Preserve search query and sort parameter in pagination links
        $appendParams = [];

        if ($request->has('search')) {
            $appendParams['search'] = $request->search;
        }

        if ($request->has('sort')) {
            $appendParams['sort'] = $request->sort;
        }

        if (!empty($appendParams)) {
            $products->appends($appendParams);
        }

        return view('shop_new', [
            'products' => $products,
            'searchQuery' => $request->search ?? ''
        ]);
    }

    public function search(Request $request)
    {
        return redirect()->route('shop', ['search' => $request->search]);
    }
}
