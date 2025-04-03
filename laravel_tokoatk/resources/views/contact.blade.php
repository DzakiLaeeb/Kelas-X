@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1>Hubungi Kami</h1>
    <div class="row mt-4">
        <div class="col-md-6">
            <form>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" required>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label">Pesan</label>
                    <textarea class="form-control" id="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Pesan</button>
            </form>
        </div>
        <div class="col-md-6">
            <h4>Informasi Kontak</h4>
            <p><i class="fas fa-map-marker-alt"></i> Jl. Contoh No. 123, Kota, Indonesia</p>
            <p><i class="fas fa-phone"></i> +62 123 4567 890</p>
            <p><i class="fas fa-envelope"></i> info@tokoatk.com</p>
        </div>
    </div>
</div>
@endsection
