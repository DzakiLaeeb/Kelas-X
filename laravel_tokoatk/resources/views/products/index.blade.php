@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <img src="{{ Storage::url($product->gambar) }}" alt="{{ $product->nama_produk }}" class="w-full h-48 object-cover">
            <div class="p-4">
                <h3 class="text-lg font-semibold mb-2">{{ $product->nama_produk }}</h3>
                <p class="text-gray-600 mb-2">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500 mb-4">Stock: {{ $product->stok }}</p>
                <div class="flex justify-between items-center">
                    <a href="{{ route('products.show', $product) }}" class="text-blue-500 hover:text-blue-700">View Details</a>
                    <button 
                        onclick="addToCart({{ $product->id }})"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 {{ $product->stok < 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                        {{ $product->stok < 1 ? 'disabled' : '' }}
                    >
                        {{ $product->stok < 1 ? 'Out of Stock' : 'Add to Cart' }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
</div>

@section('scripts')
<script>
function addToCart(productId) {
    $.ajax({
        url: '{{ route("cart.add") }}',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            product_id: productId,
            quantity: 1
        },
        success: function(response) {
            if (response.success) {
                alert('Produk berhasil ditambahkan ke keranjang!');
                window.location.reload();
            } else {
                alert(response.message || 'Gagal menambahkan produk ke keranjang.');
            }
        },
        error: function(xhr) {
            alert('Terjadi kesalahan. Silakan coba lagi.');
        }
    });
}
</script>
@endsection
@endsection


