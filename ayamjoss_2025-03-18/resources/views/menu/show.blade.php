{{-- Gunakan form dengan method POST --}}
<form action="{{ route('cart.add') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $menu->id }}">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
    </button>
</form>

