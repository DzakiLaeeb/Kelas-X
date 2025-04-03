// Fungsi untuk menambahkan ke keranjang menggunakan AJAX POST
function addToCart(menuId) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const button = document.querySelector(`button[data-menu-id="${menuId}"]`);
    
    // Disable button dan tampilkan loading state
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
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Animasi cart icon
            const navCart = document.querySelector('.nav-link[href*="cart"]');
            navCart.classList.add('bounce');
            
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
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Reset button state
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-shopping-cart"></i> Pesan';
        
        // Tampilkan error
        Swal.fire({
            title: 'Error!',
            text: 'Gagal menambahkan ke keranjang',
            icon: 'error',
            confirmButtonText: 'Ok'
        });
    });
}

function updateCart(id, action) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    fetch(`/cart/update/${id}/${action}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            window.location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}














