<form id="checkoutForm" onsubmit="event.preventDefault(); processCheckout();">
    @csrf
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Telepon</label>
        <input type="text" name="telp" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
        <label>Catatan (Opsional)</label>
        <textarea name="catatan" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">Pesan Sekarang</button>
</form>

<script>
async function processCheckout() {
    try {
        const form = document.getElementById('checkoutForm');
        const formData = new FormData(form);
        
        // Show loading
        Swal.fire({
            title: 'Memproses Pesanan',
            text: 'Mohon tunggu sebentar...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const response = await fetch('{{ route("order.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                nama: formData.get('nama'),
                email: formData.get('email'),
                telp: formData.get('telp'),
                alamat: formData.get('alamat'),
                catatan: formData.get('catatan')
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || 'Terjadi kesalahan pada server');
        }

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Pesanan Berhasil!',
                text: 'Pesanan Anda telah berhasil dibuat.',
                showConfirmButton: true,
                confirmButtonText: 'Lihat Detail Pesanan'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/order/success/${data.idorder}`;
                }
            });
        } else {
            throw new Error(data.message || 'Gagal memproses pesanan');
        }
    } catch (error) {
        console.error('Checkout error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: error.message || 'Terjadi kesalahan saat memproses pesanan'
        });
    }
}
</script>


