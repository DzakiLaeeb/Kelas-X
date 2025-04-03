@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <img src="{{ $menu->gambar }}" class="card-img-top" alt="{{ $menu->nama }}">
                <div class="card-body">
                    <h2 class="card-title">{{ $menu->nama }}</h2>
                    @if($menu->badge)
                        <span class="badge {{ $menu->badge_type }}">{{ $menu->badge }}</span>
                    @endif
                    <p class="card-text">{{ $menu->deskripsi }}</p>
                    <h3 class="price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</h3>
                    <a href="{{ route('menu.index') }}" class="btn btn-secondary">Kembali ke Menu</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

