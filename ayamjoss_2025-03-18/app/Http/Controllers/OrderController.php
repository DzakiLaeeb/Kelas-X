<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        try {
            \Log::info('Received order request:', $request->all());
            
            // Validasi data
            $request->validate([
                'nama' => 'required',
                'email' => 'required|email',
                'telp' => 'required',
                'alamat' => 'required'
            ]);

            // Cek cart dari session
            $cart = session('cart', []);
            if (empty($cart)) {
                throw new \Exception('Keranjang belanja kosong');
            }

            DB::beginTransaction();

            // Generate ID Order
            $idorder = 'ORD-' . date('YmdHis') . Str::random(4);
            
            // Hitung total
            $total = collect($cart)->sum(function($item) {
                return $item['harga'] * $item['jumlah'];
            });

            // Simpan order
            DB::table('orders')->insert([
                'idorder' => $idorder,
                'idpelanggan' => auth()->check() ? auth()->user()->idpelanggan : null,
                'tglorder' => now(),
                'total' => $total,
                'status' => 'pending',
                'nama' => $request->nama,
                'email' => $request->email,
                'telp' => $request->telp,
                'alamat' => $request->alamat,
                'catatan' => $request->catatan
            ]);

            // Simpan detail order
            foreach ($cart as $idmenu => $item) {
                DB::table('orderdetails')->insert([
                    'idorder' => $idorder,
                    'idmenu' => $idmenu,
                    'jumlah' => $item['jumlah'],
                    'harga' => $item['harga']
                ]);
            }

            // Kosongkan cart
            session()->forget('cart');

            DB::commit();

            \Log::info('Order created successfully:', ['idorder' => $idorder]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'idorder' => $idorder
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Order Error:', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}