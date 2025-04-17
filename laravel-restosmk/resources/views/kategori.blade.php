@extends('front')

@section('content')
<div class="row">
    @foreach ($menus as $menu)
    <div class="card mx-2 my-3" style="width: 18rem;">
        <img src="{{ asset('gambar/' . $menu->gambar) }}" class="card-img-top" alt="Gambar {{ $menu->menu }}">
        <div class="card-body">
            <h5 class="card-title">{{ $menu->menu }}</h5>
            <p class="card-text">{{ $menu->deskripsi }}</p>
            <h5 class="card-title">Rp{{ number_format($menu->harga, 0, ',', '.') }}</h5>
            <a href="{{ url('beli/' . $menu->idmenu) }}" class="btn btn-primary">Beli</a>
        </div>
    </div>
    @endforeach
        <div class="d-flex justify-content-center mt-2">
            {{ $menus->links() }}
        </div>
</div> 
@endsection
