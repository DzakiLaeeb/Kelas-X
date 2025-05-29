<form action="{{ route('cart.add') }}" method="POST" class="d-inline" onsubmit="event.preventDefault(); addToCart({{ $menu->id }})">
    @csrf
    <input type="hidden" name="id" value="{{ $menu->id }}">
    <button type="submit" class="btn btn-primary" data-menu-id="{{ $menu->id }}">
        <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
    </button>
</form>

<script>
    // Make sure the cart.js script is loaded
    if (typeof addToCart !== 'function') {
        function addToCart(menuId) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id: menuId
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Menu telah ditambahkan ke keranjang');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menambahkan ke keranjang');
            });
        }
    }
</script>


