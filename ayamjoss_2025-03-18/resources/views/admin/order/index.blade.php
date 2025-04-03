@extends('layouts.admin')

@section('styles')
<style>
    :root {
        --primary-red: #e60000;
        --primary-yellow: #ffcc00;
        --secondary-red: #cc0000;
        --secondary-yellow: #ffdd33;
        --light-yellow: #fff5cc;
        --dark-text: #333333;
        --light-text: #ffffff;
    }
    
    .admin-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 6px 24px rgba(0, 0, 0, 0.08);
        border: none;
        margin-bottom: 30px;
    }
    
    .card-header {
        background: linear-gradient(135deg, var(--primary-red) 0%, var(--secondary-red) 100%);
        color: var(--light-text);
        padding: 20px;
        border-bottom: none;
    }
    
    .table {
        border-collapse: separate;
        border-spacing: 0 8px;
        margin-top: -8px;
    }
    
    .table thead th {
        background-color: var(--light-yellow);
        color: var(--dark-text);
        border: none;
        padding: 12px 15px;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }
    
    .table thead th:first-child {
        border-radius: 8px 0 0 8px;
    }
    
    .table thead th:last-child {
        border-radius: 0 8px 8px 0;
    }
    
    .table tbody tr {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        border-radius: 8px;
        transition: all 0.2s ease;
        margin-bottom: 8px;
    }
    
    .table tbody tr:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        background-color: #fafafa;
    }
    
    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border: none;
        background-color: white;
    }
    
    .table tbody td:first-child {
        border-radius: 8px 0 0 8px;
        font-weight: 600;
        color: var(--primary-red);
    }
    
    .table tbody td:last-child {
        border-radius: 0 8px 8px 0;
    }
    
    .badge {
        padding: 8px 12px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge.bg-success {
        background-color: #28a745 !important;
    }
    
    .badge.bg-warning {
        background-color: var(--primary-yellow) !important;
        color: #856404 !important;
    }
    
    .badge.bg-danger {
        background-color: #dc3545 !important;
    }
    
    .badge.bg-secondary {
        background-color: #6c757d !important;
    }
    
    .btn-info {
        background-color: var(--primary-red);
        border: none;
        border-radius: 50px;
        padding: 6px 15px;
        transition: all 0.3s ease;
        color: white !important;
    }
    
    .btn-info:hover {
        background-color: var(--secondary-red);
        transform: translateX(3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    
    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
        border-radius: 8px;
        border-left: 4px solid #28a745;
        padding: 15px;
        margin-bottom: 20px;
        animation: fadeInDown 0.5s ease-out forwards;
    }
    
    .pagination {
        justify-content: center;
    }
    
    .page-item.active .page-link {
        background-color: var(--primary-red);
        border-color: var(--primary-red);
    }
    
    .page-link {
        color: var(--primary-red);
        border-radius: 5px;
        margin: 0 3px;
    }
    
    .page-link:hover {
        color: var(--secondary-red);
        background-color: var(--light-yellow);
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }
    
    .empty-state i {
        font-size: 3rem;
        color: var(--primary-yellow);
        margin-bottom: 15px;
        display: block;
    }
    
    .total-items {
        background-color: var(--light-yellow);
        border-radius: 50px;
        padding: 5px 10px;
        font-weight: 600;
        color: #856404;
    }
    
    .total-price {
        font-weight: 700;
        color: var(--primary-red);
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    .animated-table {
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    .animated-row {
        opacity: 0;
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    .animated-row:nth-child(1) { animation-delay: 0.05s; }
    .animated-row:nth-child(2) { animation-delay: 0.10s; }
    .animated-row:nth-child(3) { animation-delay: 0.15s; }
    .animated-row:nth-child(4) { animation-delay: 0.20s; }
    .animated-row:nth-child(5) { animation-delay: 0.25s; }
    .animated-row:nth-child(6) { animation-delay: 0.30s; }
    .animated-row:nth-child(7) { animation-delay: 0.35s; }
    .animated-row:nth-child(8) { animation-delay: 0.40s; }
    .animated-row:nth-child(9) { animation-delay: 0.45s; }
    .animated-row:nth-child(10) { animation-delay: 0.50s; }
    /* Tambahkan lebih banyak jika diperlukan */
</style>
@endsection

@section('content')
<div class="admin-card card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-shopping-cart me-2"></i>Daftar Pesanan
        </h5>
    </div>
    <div class="card-body p-4">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="table-responsive animated-table">
            <table class="table">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag me-1"></i>ID Order</th>
                        <th><i class="fas fa-user me-1"></i>Nama Pelanggan</th>
                        <th><i class="fas fa-shopping-basket me-1"></i>Total Items</th>
                        <th><i class="fas fa-money-bill-wave me-1"></i>Total Harga</th>
                        <th><i class="fas fa-tasks me-1"></i>Status</th>
                        <th><i class="fas fa-calendar-alt me-1"></i>Tanggal</th>
                        <th><i class="fas fa-cog me-1"></i>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="animated-row">
                            <td>#{{ $order->idorder }}</td>
                            <td>{{ $order->pelanggan->nama ?? 'Pelanggan' }}</td>
                            <td>
                                <span class="total-items">
                                    <i class="fas fa-box me-1"></i>{{ $order->details->sum('jumlah') }} items
                                </span>
                            </td>
                            <td class="total-price">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 
                                    ($order->status == 'processing' ? 'warning' : 
                                    ($order->status == 'cancelled' ? 'danger' : 'secondary')) }}">
                                    @if($order->status == 'completed')
                                        <i class="fas fa-check-circle me-1"></i>
                                    @elseif($order->status == 'processing')
                                        <i class="fas fa-spinner me-1"></i>
                                    @elseif($order->status == 'cancelled')
                                        <i class="fas fa-times-circle me-1"></i>
                                    @else
                                        <i class="fas fa-clock me-1"></i>
                                    @endif
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <i class="far fa-calendar-alt me-1"></i>{{ $order->created_at->format('d/m/Y') }}
                                <br>
                                <small class="text-muted"><i class="far fa-clock me-1"></i>{{ $order->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.order.show', $order->idorder) }}" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <i class="fas fa-shopping-cart"></i>
                                    <h5>Belum ada pesanan</h5>
                                    <p class="text-muted">Pesanan baru akan muncul di sini</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection


