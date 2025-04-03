<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as user_name')
            ->orderBy('orders.created_at', 'desc')
            ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = DB::table('orders')
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->select('orders.*', 'users.name as user_name', 'users.email', 'products.nama_produk as product_name', 'products.gambar as product_image')
            ->where('orders.id', $id)
            ->first();

        if (!$order) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Order not found');
        }

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        DB::table('orders')
            ->where('id', $id)
            ->update(['status' => $request->status]);

        return redirect()->route('admin.orders.show', $id)
            ->with('success', 'Status pesanan berhasil diperbarui');
    }
}
