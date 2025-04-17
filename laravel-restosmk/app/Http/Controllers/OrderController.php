<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::join('pelanggan','pelanggan.idpelanggan','=','pelanggan.idpelanggan')
                    ->select(['order.*','pelanggan.*'])
                    ->orderby('status','ASC')
                    ->paginate(2);

        return view('Backend.order.select',['orders'=>$orders]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreOrderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($idorder)
    {
        $order = Order::where('idorder',$idorder)->first();
        return view('Backend.order.update',['order'=>$order]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit($idorder)
    {
        $orders = Order::join('order_details','orders.idorder','=','order_details.idorder')
                    ->join('menus','orders.idmenu','=','menus.idmenu')
                    ->join('pelanggan','orders.idpelanggan','=','pelanggan.idpelanggan')
                    ->where('orders.idorder',$idorder)
                    ->get(['orders.*','order_details.*','menus.*','pelanggans.*']);

        return view('Backend.order.detail',['orders'=>$orders]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateOrderRequest  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idorder)
    {
        $data = $request->validate([
            'bayar' => 'required|numeric',
        ]);

        $kembali = Order::where('idorder',$idorder)->first();
        $kembali = $data['bayar']-$kembalis->total;
        

        Order::where('idorder',$idorder)->update([
            'bayar' => $data['bayar'],
            'kembali' => $kembali,
            'status' => 1,
        ]);

        return redirect('admin/order');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
