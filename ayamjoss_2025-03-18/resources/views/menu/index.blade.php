<form action="{{ route('cart.add') }}" method="POST" class="d-inline">
    @csrf
    <input type="hidden" name="id" value="{{ $menu->id }}">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
    </button>
</form>


