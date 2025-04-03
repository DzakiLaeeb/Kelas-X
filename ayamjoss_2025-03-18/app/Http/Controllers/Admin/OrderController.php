<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['details.menu', 'pelanggan'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('admin.order.index', compact('orders'));
    }

    public function show($idorder)
    {
        $order = Order::with(['details.menu', 'pelanggan'])
                     ->where('idorder', $idorder)
                     ->firstOrFail();
        
        return view('admin.order.show', compact('order'));
    }

    public function updateStatus(Request $request, $idorder)
    {
        $order = Order::findOrFail($idorder);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diupdate!');
    }
}

