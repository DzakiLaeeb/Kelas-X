-- Update descriptions for existing products
UPDATE products 
SET description = 'Spesifikasi Produk:
- Merk: Standard
- Warna Tinta: Biru
- Ukuran Mata Pena: 0.5mm
- Material: Plastik Berkualitas
- Cocok untuk: Menulis, Menggambar

Keunggulan:
- Tinta yang lancar dan konsisten
- Nyaman digenggam
- Tidak mudah bocor
- Tahan lama
- Ideal untuk pelajar dan professional'
WHERE name LIKE '%Pulpen%' OR name LIKE '%Pena%';

UPDATE products 
SET description = 'Spesifikasi Produk:
- Ukuran: A4 (210 x 297 mm)
- Berat: 70 GSM
- Warna: Putih
- Isi: 500 lembar/rim

Keunggulan:
- Permukaan halus
- Tidak mudah sobek
- Ideal untuk print dan fotokopi
- Kualitas premium
- Hasil cetak tajam dan jelas'
WHERE name LIKE '%Kertas%' OR name LIKE '%Paper%';

UPDATE products 
SET description = 'Spesifikasi Produk:
- Ukuran: 21 x 15 cm
- Jumlah Halaman: 58 lembar
- Cover: Hard cover
- Kertas: HVS berkualitas

Fitur:
- Halaman bergaris
- Cover anti air
- Binding kuat
- Desain modern
- Cocok untuk sekolah dan kantor'
WHERE name LIKE '%Buku%' OR name LIKE '%Book%';

-- You can add more specific descriptions for other product types
