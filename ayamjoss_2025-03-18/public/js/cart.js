function addToCart(menuId) {
    console.log('Adding to cart, menuId:', menuId);
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const button = document.querySelector(`button[data-menu-id="${menuId}"]`);

    // Disable button and show loading state
    if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';
    } else {
        console.warn('Button not found for menuId:', menuId);
    }

    // Buat FormData untuk mengirim data
    const formData = new FormData();
    formData.append('id', menuId);
    formData.append('_token', token);

    console.log('Sending data with menuId:', menuId);

    fetch('/cart/add', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.status);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
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
            if (button) {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-shopping-cart"></i> Pesan';
            }

            // Tampilkan notifikasi
            if (typeof Swal !== 'undefined') {
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
                alert('Menu telah ditambahkan ke keranjang');
                console.log('SweetAlert not available, using basic alert');
            }
        } else {
            throw new Error(data.message || 'Gagal menambahkan ke keranjang');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (button) {
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-shopping-cart"></i> Pesan';
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error!',
                text: 'Gagal menambahkan ke keranjang: ' + error.message,
                icon: 'error',
                confirmButtonText: 'Ok'
            });
        } else {
            alert('Gagal menambahkan ke keranjang: ' + error.message);
        }
    });
}



