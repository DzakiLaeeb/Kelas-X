@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random&size=128" 
                             alt="{{ auth()->user()->name }}" class="rounded-circle img-fluid" style="width: 128px; height: 128px;">
                    </div>
                    <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                    <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                    <p class="mb-0">
                        <span class="badge bg-primary">{{ auth()->user()->is_admin ? 'Admin' : 'Pelanggan' }}</span>
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user me-2"></i> Profil Saya
                        </a>
                        <a href="{{ route('orders') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-shopping-bag me-2"></i> Pesanan Saya
                        </a>
                        <a href="{{ route('settings') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-cog me-2"></i> Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Pesanan #{{ $order->id }}</h5>
                    <a href="{{ route('orders') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Informasi Pesanan</h6>
                            <p class="mb-1"><strong>ID Pesanan:</strong> #{{ $order->id }}</p>
                            <p class="mb-1"><strong>Tanggal Pesanan:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</p>
                            <p class="mb-1">
                                <strong>Status:</strong>
                                @php
                                    $statusClass = '';
                                    switch($order->status) {
                                        case 'pending':
                                            $statusClass = 'bg-warning';
                                            $statusText = 'Menunggu Pembayaran';
                                            break;
                                        case 'processing':
                                            $statusClass = 'bg-info';
                                            $statusText = 'Diproses';
                                            break;
                                        case 'shipped':
                                            $statusClass = 'bg-primary';
                                            $statusText = 'Dikirim';
                                            break;
                                        case 'delivered':
                                            $statusClass = 'bg-success';
                                            $statusText = 'Selesai';
                                            break;
                                        case 'cancelled':
                                            $statusClass = 'bg-danger';
                                            $statusText = 'Dibatalkan';
                                            break;
                                        default:
                                            $statusClass = 'bg-secondary';
                                            $statusText = $order->status;
                                    }
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Informasi Pengiriman</h6>
                            <p class="mb-1"><strong>Nama:</strong> {{ $order->shipping_name }}</p>
                            <p class="mb-1"><strong>Alamat:</strong> {{ $order->shipping_address }}</p>
                            <p class="mb-1"><strong>Telepon:</strong> {{ $order->shipping_phone }}</p>
                        </div>
                    </div>
                    
                    <h6 class="text-muted mb-3">Item Pesanan</h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Harga</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderItems as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product_image)
                                                    @if(Str::startsWith($item->product_image, ['http://', 'https://']))
                                                        <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" 
                                                             class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <img src="{{ asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name }}" 
                                                             class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                @else
                                                    <img src="https://via.placeholder.com/50" alt="{{ $item->product_name }}" 
                                                         class="img-thumbnail me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Subtotal</strong></td>
                                    <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Ongkos Kirim</strong></td>
                                    <td class="text-end">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end"><strong>Rp {{ number_format($order->total, 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            @if($order->status == 'pending')
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">Petunjuk Pembayaran</h6>
                        <p>Silakan transfer pembayaran ke rekening berikut:</p>
                        <div class="alert alert-info">
                            <p class="mb-1"><strong>Bank BCA</strong></p>
                            <p class="mb-1">No. Rekening: 1234567890</p>
                            <p class="mb-0">Atas Nama: PT Toko ATK Indonesia</p>
                        </div>
                        <p>Setelah melakukan pembayaran, silakan konfirmasi melalui WhatsApp ke nomor <strong>081234567890</strong> dengan menyertakan bukti transfer dan ID pesanan.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
