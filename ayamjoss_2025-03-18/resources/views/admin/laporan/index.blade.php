@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Cards -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Penjualan Hari Ini</h5>
                    <h3>Rp {{ number_format($today_sales, 0, ',', '.') }}</h3>
                    <p>{{ $today_orders }} pesanan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Penjualan Bulan Ini</h5>
                    <h3>Rp {{ number_format($month_sales, 0, ',', '.') }}</h3>
                    <p>{{ $month_orders }} pesanan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Total Pelanggan</h5>
                    <h3>{{ $total_customers }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card mt-4">
        <div class="card-body">
            <h5>Grafik Penjualan</h5>
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Pastikan data valid sebelum membuat chart
const labels = @json($chart_labels);
const data = @json($chart_data);

// Tunggu sampai DOM selesai dimuat
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Penjualan',
                data: data,
                borderColor: '#4e73df',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
@endsection

