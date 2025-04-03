@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 64px;"></i>
                    <h2 class="mt-3">Pesanan Berhasil!</h2>
                    <p class="text-muted">ID Pesanan: {{ $order->idorder }}</p>
                    
                    <div class="alert alert-info">
                        <h5>Detail Pengiriman:</h5>
                        <p class="mb-1">Nama: {{ $order->nama }}</p>
                        <p class="mb-1">Alamat: {{ $order->alamat }}</p>
                        <p class="mb-1">Telepon: {{ $order->telp }}</p>
                    </div>

                    <div class="table-responsive mt-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderDetails as $detail)
                                <tr>
                                    <td>{{ $detail->menu }}</td>
                                    <td>{{ $detail->jumlah }}</td>
                                    <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                                <tr class="table-primary">
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('menu.index') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Kembali ke Menu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

