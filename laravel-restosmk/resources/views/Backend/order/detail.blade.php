@extends('Backend.back')

@section('admincontent')
    <div>
        <h1>Order Detail</h1>
    </div>

    <div>
        <h1>Pelanggann : {{ ($orders[0]->total) }}</h1>
        <h1>Total : {{ number_format($orders[0]->total) }}</h1>
    </div>

    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Menu</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            @php
                $no=1;
            @endphp
            <tbody>
                @foreach ($orders as $order)

                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $order->menu }}</td>
                            <td>{{ $order->harga }}</td>
                            <td>{{ $order->jumlah * $order->harga }}</td>
                            <td>0</td>
                        </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $orders->withQueryString()->links() }}
    </div>
@endsection