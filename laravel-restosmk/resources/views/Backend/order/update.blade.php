@extends('Backend.back')

@section('admincontent')
<div class="row">
    <div>
        <h1>{{ number_format($order->total) }}</h1>
    </div>
    <div class="col-6">
        <form action="{{ url('admin/order/'.$order->idorder) }}" method="POST">
            @csrf
            @method ('PUT')
            <div class="mt-2">
                <label class="form-label">Tanggal</label>
                <input class="form-control" min="{{ $order->total }}" value="{{ $order->total }}" type="text"  name="total" id="">
                <span>
                    @error('tglorder')
                        {{ $message }}
                    @enderror
                </span>
            </div>


            
            <div class="mt-2">
                <label class="form-label">Total</label>
                <input class="form-control" value="{{ $order->total }}" name="total" type="text" required>
                <span>
                    @error('total')
                        {{ $message }}
                    @enderror
                </span>
            </div>
                        
            <div class="mt-4">
                <button class="btn btn-primary" type="submit">Bayar</button>
            </div>
    </div>
</div>
@endsection