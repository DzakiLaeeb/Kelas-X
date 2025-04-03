function addToCart(menuId) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const button = document.querySelector(`button[data-menu-id="${menuId}"]`);
    
    // Disable button and show loading state
    button.disabled = true;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';
    
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
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if(data.success) {
            // Animasi cart icon
            const navCart = document.querySelector('.nav-link[href*="cart"]');
            if (navCart) {
                navCart.classList.add('bounce');
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    cartCount.textContent = data.cart_count;
                }
            }
            
            // Reset button state
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-shopping-cart"></i> Pesan';
            
            // Tampilkan notifikasi
            Swal.fire({
                title: 'Berhasil!',
                text: 'Menu telah ditambahkan ke keranjang',
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Lihat Keranjang',
                cancelButtonText: 'Lanjut Belanja'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/cart';
                }
            });
        } else {
            throw new Error(data.message || 'Gagal menambahkan ke keranjang');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-shopping-cart"></i> Pesan';
        
        Swal.fire({
            title: 'Error!',
            text: 'Gagal menambahkan ke keranjang',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    });
}



