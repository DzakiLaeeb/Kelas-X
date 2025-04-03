@extends('layouts.app')

@section('title', 'Pesanan Saya')

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
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0">Riwayat Pesanan</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(count($orders) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID Pesanan</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}</td>
                                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                            <td>
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
                                            </td>
                                            <td>
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <img src="https://img.icons8.com/fluency/96/000000/empty-box.png" alt="Empty" class="img-fluid mb-3" style="width: 96px; height: 96px;">
                            <h5>Belum Ada Pesanan</h5>
                            <p class="text-muted">Anda belum memiliki pesanan. Mulai belanja sekarang!</p>
                            <a href="{{ route('shop') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i> Belanja Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
