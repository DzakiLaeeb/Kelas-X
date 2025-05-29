# Setup Instructions untuk Fitur Beli Menu

## 1. Setup Database

Jalankan SQL berikut di phpMyAdmin atau MySQL client:

```sql
-- Buat tabel orders jika belum ada
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    menu_id INT NOT NULL,
    nama_menu VARCHAR(255) NOT NULL,
    harga_satuan DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    total_harga DECIMAL(10,2) NOT NULL,
    customer_name VARCHAR(255) NOT NULL DEFAULT 'Guest User',
    customer_phone VARCHAR(20) DEFAULT '',
    notes TEXT DEFAULT '',
    status ENUM('pending', 'confirmed', 'preparing', 'ready', 'delivered', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign key constraint (optional)
    FOREIGN KEY (menu_id) REFERENCES menu(id) ON DELETE CASCADE,
    
    -- Indexes untuk performance
    INDEX idx_menu_id (menu_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    INDEX idx_customer_name (customer_name)
);
```

## 2. Fitur yang Ditambahkan

### A. Tampilan Menu yang Diperbaiki
- ✅ **Gambar lebih besar**: Dari 80px menjadi 200px (desktop), 150px (tablet), 120px (mobile)
- ✅ **Tombol Beli**: Tombol gradient biru yang menarik dengan efek hover
- ✅ **Loading state**: Tombol menampilkan "Memproses..." saat sedang menyimpan
- ✅ **Format harga**: Menampilkan format Rupiah yang benar

### B. Sistem Pemesanan
- ✅ **API add_order.php**: Endpoint untuk menyimpan pesanan ke database
- ✅ **Validasi data**: Memastikan data yang masuk valid
- ✅ **Error handling**: Menangani error dengan baik
- ✅ **Response feedback**: Memberikan notifikasi sukses/gagal

### C. Halaman Daftar Pesanan
- ✅ **OrdersPage**: Halaman untuk melihat semua pesanan
- ✅ **Filter status**: Filter pesanan berdasarkan status
- ✅ **Design menarik**: Card layout dengan gradient dan efek hover
- ✅ **Responsive**: Tampilan yang bagus di semua device
- ✅ **API get_orders.php**: Endpoint untuk mengambil data pesanan

## 3. Cara Menggunakan

### Untuk Customer:
1. Buka halaman **Menu** (`/menu`)
2. Pilih menu yang diinginkan
3. Klik tombol **"Beli Sekarang"** 🛒
4. Sistem akan menyimpan pesanan ke database
5. Akan muncul notifikasi sukses/gagal

### Untuk Admin/Staff:
1. Buka halaman **Daftar Pesanan** (`/orders`)
2. Lihat semua pesanan yang masuk
3. Filter berdasarkan status jika diperlukan
4. Klik **Refresh** untuk update data terbaru

## 4. Struktur Database Orders

| Field | Type | Description |
|-------|------|-------------|
| id | INT | Primary key |
| menu_id | INT | ID menu yang dipesan |
| nama_menu | VARCHAR(255) | Nama menu |
| harga_satuan | DECIMAL(10,2) | Harga per item |
| quantity | INT | Jumlah pesanan |
| total_harga | DECIMAL(10,2) | Total harga |
| customer_name | VARCHAR(255) | Nama pelanggan |
| customer_phone | VARCHAR(20) | Nomor telepon |
| notes | TEXT | Catatan khusus |
| status | ENUM | Status pesanan |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

## 5. Status Pesanan

- **pending**: Menunggu konfirmasi
- **confirmed**: Dikonfirmasi
- **preparing**: Sedang diproses
- **ready**: Siap diambil
- **delivered**: Sudah diantar
- **cancelled**: Dibatalkan

## 6. File yang Ditambahkan/Dimodifikasi

### File Baru:
- `api/add_order.php` - API untuk menambah pesanan
- `api/get_orders.php` - API untuk mengambil data pesanan
- `src/pages/OrdersPage.jsx` - Halaman daftar pesanan
- `src/styles/Orders.css` - Styling untuk halaman orders
- `database/create_orders_table.sql` - SQL untuk membuat tabel

### File yang Dimodifikasi:
- `src/pages/MenuPage.jsx` - Tambah tombol beli dan fungsi handleBuyItem
- `src/styles/Menu.css` - Perbesar gambar dan styling tombol beli
- `src/App.jsx` - Tambah route untuk OrdersPage
- `src/layouts/PublicLayout.jsx` - Tambah menu "Daftar Pesanan"

## 7. Cara Testing

1. **Test Pemesanan**:
   - Buka `/menu`
   - Klik tombol "Beli Sekarang" pada menu manapun
   - Cek apakah muncul notifikasi sukses
   - Cek database apakah data tersimpan

2. **Test Daftar Pesanan**:
   - Buka `/orders`
   - Cek apakah pesanan muncul
   - Test filter berdasarkan status
   - Test tombol refresh

## 8. Pengembangan Selanjutnya

Fitur yang bisa ditambahkan:
- Form input detail customer (nama, telepon, alamat)
- Sistem login customer
- Update status pesanan
- Notifikasi real-time
- Print receipt
- Payment integration
- Order tracking
