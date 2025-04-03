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
    
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    }
    
    .card-title {
        color: var(--primary-red);
        font-weight: 600;
        margin-bottom: 15px;
        border-bottom: 2px solid var(--primary-yellow);
        padding-bottom: 10px;
        display: inline-block;
    }
    
    .btn-secondary {
        background-color: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        border-radius: 50px;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background-color: rgba(255, 255, 255, 0.3);
        transform: translateX(-5px);
    }
    
    .btn-primary {
        background-color: var(--primary-red);
        border: none;
        border-radius: 50px;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background-color: var(--secondary-red);
        transform: translateX(5px);
    }
    
    .form-select {
        border-radius: 50px 0 0 50px;
        border: 1px solid #ced4da;
        padding: 10px 15px;
    }
    
    .form-select:focus {
        border-color: var(--primary-yellow);
        box-shadow: 0 0 0 0.25rem rgba(255, 204, 0, 0.25);
    }
    
    .table {
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    
    .table thead th {
        background-color: var(--light-yellow);
        color: var(--dark-text);
        border: none;
        padding: 12px 15px;
        font-weight: 600;
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
    }
    
    .table tbody tr:hover {
        transform: scale(1.01);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    
    .table tbody td {
        padding: 15px;
        vertical-align: middle;
        border: none;
        background-color: white;
    }
    
    .table tbody td:first-child {
        border-radius: 8px 0 0 8px;
    }
    
    .table tbody td:last-child {
        border-radius: 0 8px 8px 0;
    }
    
    .img-thumbnail {
        border-radius: 8px;
        border: 2px solid var(--primary-yellow);
        padding: 3px;
        transition: all 0.3s ease;
    }
    
    .img-thumbnail:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .status-badge {
        padding: 8px 15px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-block;
        margin-bottom: 15px;
    }
    
    .status-pending {
        background-color: var(--light-yellow);
        color: #856404;
    }
    
    .status-processing {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    
    .status-completed {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }
    
    .customer-info-card {
        border-left: 4px solid var(--primary-red);
    }
    
    .order-status-card {
        border-left: 4px solid var(--primary-yellow);
    }
    
    .info-label {
        color: #6c757d;
        font-weight: 500;
    }
    
    .info-value {
        font-weight: 500;
    }
    
    .total-row {
        background-color: var(--light-yellow) !important;
        font-weight: bold;
    }
    
    .total-price {
        color: var(--primary-red);
        font-size: 1.2rem;
        font-weight: 700;
    }
    
    .note-section {
        background-color: var(--light-yellow);
        border-radius: 8px;
        padding: 15px;
        border-left: 4px solid var(--primary-yellow);
        margin-top: 20px;
    }
    
    .note-title {
        color: var(--primary-red);
        font-weight: 600;
        margin-bottom: 10px;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animated-card {
        animation: fadeIn 0.5s ease-out forwards;
    }
    
    .delay-1 {
        animation-delay: 0.1s;
    }
    
    .delay-2 {
        animation-delay: 0.2s;
    }
    
    .delay-3 {
        animation-delay: 0.3s;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="admin-card card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-utensils me-2"></i>Detail Pesanan #{{ $order->idorder }}
            </h5>
            <a href="{{ route('admin.order.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
        <div class="card-body p-4">
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card customer-info-card animated-card delay-1">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-user-circle me-2"></i>Informasi Pelanggan
                            </h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td width="130" class="info-label">
                                        <i class="fas fa-user me-1"></i> Nama
                                    </td>
                                    <td class="info-value">{{ $order->pelanggan->nama ?? 'Pelanggan' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">
                                        <i class="fas fa-envelope me-1"></i> Email
                                    </td>
                                    <td class="info-value">{{ $order->pelanggan->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">
                                        <i class="fas fa-phone me-1"></i> Telepon
                                    </td>
                                    <td class="info-value">{{ $order->pelanggan->telp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="info-label">
                                        <i class="fas fa-map-marker-alt me-1"></i> Alamat
                                    </td>
                                    <td class="info-value">{{ $order->alamat ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card order-status-card animated-card delay-2">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-tasks me-2"></i>Status Pesanan
                            </h6>
                            
                            <div class="mb-3">
                                <span class="status-badge status-{{ $order->status }}">
                                    @if($order->status == 'pending')
                                        <i class="fas fa-clock me-1"></i>Pending
                                    @elseif($order->status == 'processing')
                                        <i class="fas fa-spinner me-1"></i>Processing
                                    @elseif($order->status == 'completed')
                                        <i class="fas fa-check-circle me-1"></i>Completed
                                    @elseif($order->status == 'cancelled')
                                        <i class="fas fa-times-circle me-1"></i>Cancelled
                                    @endif
                                </span>
                            </div>
                            
                            <form action="{{ route('admin.order.update-status', $order->idorder) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <select name="status" class="form-select">
                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-sync-alt me-1"></i>Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card animated-card delay-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-shopping-cart me-2"></i>Detail Item
                    </h6>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Gambar</th>
                                    <th>Menu</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->details as $detail)
                                    <tr>
                                        <td>
                                            <img src="{{ $detail->menu->gambar }}" 
                                                alt="{{ $detail->menu->menu }}" 
                                                class="img-thumbnail"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                        </td>
                                        <td class="fw-medium">{{ $detail->menu->menu }}</td>
                                        <td>Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                {{ $detail->jumlah }}
                                            </span>
                                        </td>
                                        <td>Rp {{ number_format($detail->harga * $detail->jumlah, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total Pembayaran:</td>
                                    <td class="total-price">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if($order->catatan)
                        <div class="note-section">
                            <h6 class="note-title">
                                <i class="fas fa-sticky-note me-2"></i>Catatan:
                            </h6>
                            <p class="mb-0">{{ $order->catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection