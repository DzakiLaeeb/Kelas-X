@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="text-center mb-4">Tentang Kami</h1>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4">Selamat Datang di TokoATK</h2>
                    <p>Kami adalah toko alat tulis terpercaya yang menyediakan berbagai kebutuhan alat tulis kantor dan sekolah dengan kualitas terbaik.</p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="h5">Visi Kami</h3>
                            <p>Menjadi toko alat tulis terdepan yang menyediakan produk berkualitas dengan harga terjangkau.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h3 class="h5">Misi Kami</h3>
                            <p>Memberikan pelayanan terbaik dan produk berkualitas untuk memenuhi kebutuhan alat tulis pelanggan kami.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h4">Mengapa Memilih Kami?</h2>
                    <ul class="list-unstyled">
                        <li class="mb-2">✓ Produk Berkualitas</li>
                        <li class="mb-2">✓ Harga Bersaing</li>
                        <li class="mb-2">✓ Pelayanan Ramah</li>
                        <li class="mb-2">✓ Pengiriman Cepat</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
        border: none;
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }

    h1 {
        color: #333;
        font-weight: 600;
    }

    .h4, .h5 {
        color: #2c3e50;
        font-weight: 600;
    }
</style>
@endpush

