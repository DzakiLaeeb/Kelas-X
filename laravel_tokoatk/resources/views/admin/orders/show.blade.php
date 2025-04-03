@extends('layouts.admin')

@section('title', 'Order Details')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Order #{{ $order->order_number ?? $order->id }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Orders
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Order Information Card -->
        <div class="col-lg-5 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Order Information</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Order Actions:</div>
                            <a class="dropdown-item" href="#"><i class="fas fa-print fa-sm fa-fw me-2 text-gray-400"></i> Print Invoice</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"><i class="fas fa-envelope fa-sm fa-fw me-2 text-gray-400"></i> Email Customer</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-xs font-weight-bold text-uppercase mb-1 text-gray-600">Status</span>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning text-dark px-2 py-1">Pending</span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-info px-2 py-1">Processing</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-success px-2 py-1">Completed</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger px-2 py-1">Cancelled</span>
                            @else
                                <span class="badge bg-secondary px-2 py-1">{{ ucfirst($order->status ?? 'Unknown') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th class="ps-0" style="width: 40%;">Order Number:</th>
                                    <td class="text-gray-800">{{ $order->order_number ?? $order->id }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Date:</th>
                                    <td class="text-gray-800">{{ is_string($order->created_at) ? date('d M Y, H:i', strtotime($order->created_at)) : $order->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Customer:</th>
                                    <td class="text-gray-800">{{ $order->user_name ?? 'Guest' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Email:</th>
                                    <td class="text-gray-800">{{ $order->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-0">Total Amount:</th>
                                    <td class="text-gray-800 font-weight-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        <h6 class="font-weight-bold mb-3">Update Order Status</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="pending">
                                <button type="submit" class="btn btn-outline-warning btn-sm px-3 mb-2 {{ $order->status == 'pending' ? 'active' : '' }}">
                                    <i class="fas fa-clock me-1"></i> Pending
                                </button>
                            </form>

                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="processing">
                                <button type="submit" class="btn btn-outline-info btn-sm px-3 mb-2 {{ $order->status == 'processing' ? 'active' : '' }}">
                                    <i class="fas fa-spinner me-1"></i> Processing
                                </button>
                            </form>

                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <button type="submit" class="btn btn-outline-success btn-sm px-3 mb-2 {{ $order->status == 'completed' ? 'active' : '' }}">
                                    <i class="fas fa-check me-1"></i> Completed
                                </button>
                            </form>

                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn btn-outline-danger btn-sm px-3 mb-2 {{ $order->status == 'cancelled' ? 'active' : '' }}">
                                    <i class="fas fa-times me-1"></i> Cancelled
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Card -->
        <div class="col-lg-7 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50%;">Product</th>
                                    <th scope="col" class="text-center">Price</th>
                                    <th scope="col" class="text-center">Qty</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($order->product_image)
                                                <div class="flex-shrink-0 me-3">
                                                    @if(Str::startsWith($order->product_image, ['http://', 'https://']))
                                                        <img src="{{ $order->product_image }}" alt="{{ $order->product_name }}" width="60" height="60" class="img-thumbnail">
                                                    @else
                                                        <img src="{{ asset('storage/' . $order->product_image) }}" alt="{{ $order->product_name }}" width="60" height="60" class="img-thumbnail">
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $order->product_name ?? 'Unknown Product' }}</h6>
                                                <small class="text-muted">Product ID: {{ $order->product_id ?? 'N/A' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                                    <td class="text-center align-middle">{{ $order->quantity }}</td>
                                    <td class="text-end align-middle font-weight-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end">Shipping:</td>
                                    <td class="text-end">Rp 0</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                    <td class="text-end fw-bold fs-5">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
