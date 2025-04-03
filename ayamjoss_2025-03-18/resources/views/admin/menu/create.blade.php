
@extends('layouts.admin')

@section('styles')
<style>
    :root {
        --primary-red: #e63946;
        --primary-yellow: #ffb703;
        --secondary-yellow: #ffd166;
        --dark-red: #9e2a2b;
        --light-yellow: #fff1d0;
        --dark-text: #333333;
        --light-text: #ffffff;
        --light-gray: #f8f9fa;
        --border-gray: #ddd;
    }

    /* General Layout */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .card-header {
        background-color: var(--primary-red);
        color: var(--light-text);
        padding: 1.5rem;
        border-bottom: 3px solid var(--primary-yellow);
    }

    .card-body {
        padding: 2rem;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .page-title {
        margin: 0;
        font-size: 1.8rem;
        font-weight: 700;
    }

    /* Form Sections */
    .form-section {
        background-color: var(--light-gray);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid var(--primary-yellow);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: var(--dark-red);
        border-bottom: 2px solid var(--secondary-yellow);
        padding-bottom: 0.5rem;
        display: inline-block;
    }

    /* Form Controls */
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }

    .form-column {
        flex: 1;
        padding: 0 10px;
        min-width: 250px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--dark-text);
    }

    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-gray);
        border-radius: 4px;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-yellow);
        box-shadow: 0 0 0 3px rgba(255, 183, 3, 0.25);
    }

    .form-select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-gray);
        border-radius: 4px;
        font-size: 1rem;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 12px;
    }

    .form-select:focus {
        outline: none;
        border-color: var(--primary-yellow);
        box-shadow: 0 0 0 3px rgba(255, 183, 3, 0.25);
    }

    .input-group {
        display: flex;
        align-items: stretch;
    }

    .input-group-text {
        padding: 0.75rem;
        background-color: var(--light-yellow);
        border: 1px solid var(--border-gray);
        border-right: none;
        border-radius: 4px 0 0 4px;
        color: var(--dark-text);
        font-weight: 600;
    }

    .input-group .form-control {
        border-radius: 0 4px 4px 0;
        flex: 1;
    }

    .form-text {
        font-size: 0.875rem;
        color: #666;
        margin-top: 0.25rem;
    }

    /* Image Preview */
    .image-preview {
        width: 100%;
        height: 200px;
        border-radius: 8px;
        border: 2px dashed var(--secondary-yellow);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        overflow: hidden;
        background-color: var(--light-gray);
    }

    .image-preview img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    /* Badge Preview */
    .badge-preview {
        display: inline-block;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 4px;
        margin-left: 1rem;
    }

    .badge-normal {
        background-color: var(--primary-yellow);
        color: var(--dark-text);
    }

    .badge-hot {
        background-color: var(--primary-red);
        color: var(--light-text);
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
    }

    .btn-primary {
        background-color: var(--primary-red);
        color: var(--light-text);
    }

    .btn-primary:hover {
        background-color: var(--dark-red);
    }

    .btn-outline {
        background-color: transparent;
        color: var(--dark-text);
        border: 2px solid var(--border-gray);
    }

    .btn-outline:hover {
        border-color: var(--primary-yellow);
        background-color: var(--light-yellow);
    }

    .button-group {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    /* Validation */
    .is-invalid {
        border-color: var(--primary-red) !important;
    }

    .invalid-feedback {
        color: var(--primary-red);
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .required {
        color: var(--primary-red);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-column {
            flex: 100%;
        }
        
        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .button-group {
            flex-direction: column;
            width: 100%;
        }
        
        .btn {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="header-content">
                <h3 class="page-title">Tambah Menu Baru</h3>
                <a href="{{ route('admin.menu.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left" style="margin-right: 0.5rem;"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Basic Information Section -->
                <div class="form-section">
                    <div class="section-title">Informasi Dasar</div>
                    
                    <div class="form-row">
                        <div class="form-column">
                            <div class="form-group">
                                <label class="form-label">Nama Menu <span class="required">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                       name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama menu">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-column">
                            <div class="form-group">
                                <label class="form-label">Kategori <span class="required">*</span></label>
                                <select class="form-select @error('kategori_id') is-invalid @enderror" name="kategori_id">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Deskripsi <span class="required">*</span></label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  name="deskripsi" rows="3" placeholder="Deskripsikan menu ini">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Harga (Rp) <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                                   name="harga" value="{{ old('harga') }}" placeholder="0">
                        </div>
                        @error('harga')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <!-- Image Section -->
                <div class="form-section">
                    <div class="section-title">Gambar Menu</div>
                    
                    <div class="image-preview" id="imagePreview">
                        <div class="text-center" id="previewPlaceholder" style="color: #666;">
                            <i class="fas fa-image" style="font-size: 3rem; margin-bottom: 0.5rem;"></i>
                            <p>Preview gambar akan muncul di sini</p>
                        </div>
                        <img src="{{ old('gambar') }}" id="previewImage" style="display: none;">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-column">
                            <div class="form-group">
                                <label class="form-label">Upload Gambar <span class="required">*</span></label>
                                <input type="file" class="form-control @error('gambar_file') is-invalid @enderror" 
                                       name="gambar_file" id="gambarFile" accept="image/*">
                                <div class="form-text">Format yang didukung: JPG, PNG, GIF. Ukuran maksimal: 2MB</div>
                                @error('gambar_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-column">
                            <div class="form-group">
                                <label class="form-label">Atau gunakan URL Gambar</label>
                                <input type="url" class="form-control @error('gambar') is-invalid @enderror" 
                                       name="gambar" id="gambarUrl" value="{{ old('gambar') }}" 
                                       placeholder="https://example.com/image.jpg">
                                @error('gambar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Badge Section -->
                <div class="form-section">
                    <div class="section-title">Badge (Opsional)</div>
                    
                    <div class="form-row">
                        <div class="form-column">
                            <div class="form-group">
                                <label class="form-label">Teks Badge</label>
                                <input type="text" class="form-control" name="badge" id="badgeText"
                                       value="{{ old('badge') }}" placeholder="Contoh: Baru, Promo, Favorit">
                            </div>
                        </div>
                        
                        <div class="form-column">
                            <div class="form-group">
                                <label class="form-label">Tipe Badge</label>
                                <select class="form-select" name="badge_type" id="badgeType">
                                    <option value="">Pilih Type</option>
                                    <option value="normal" {{ old('badge_type') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="hot" {{ old('badge_type') == 'hot' ? 'selected' : '' }}>Hot</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-column" style="flex: 0 0 auto; display: flex; align-items: flex-end;">
                            <div id="badgePreview" class="badge-preview" style="display: none;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="button-group">
                    <a href="{{ route('admin.menu.index') }}" class="btn btn-outline">
                        <i class="fas fa-times" style="margin-right: 0.5rem;"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save" style="margin-right: 0.5rem;"></i> Simpan Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview functionality
        const gambarFile = document.getElementById('gambarFile');
        const gambarUrl = document.getElementById('gambarUrl');
        const previewImage = document.getElementById('previewImage');
        const previewPlaceholder = document.getElementById('previewPlaceholder');
        
        // Handle file upload preview
        gambarFile.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    previewPlaceholder.style.display = 'none';
                    // Clear URL input when file is selected
                    gambarUrl.value = '';
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Handle URL input preview
        gambarUrl.addEventListener('input', function() {
            if (this.value) {
                previewImage.src = this.value;
                previewImage.style.display = 'block';
                previewPlaceholder.style.display = 'none';
                // Clear file input when URL is entered
                gambarFile.value = '';
            } else {
                previewImage.style.display = 'none';
                previewPlaceholder.style.display = 'block';
            }
        });
        
        // Initialize image preview if URL exists
        if (gambarUrl.value) {
            previewImage.src = gambarUrl.value;
            previewImage.style.display = 'block';
            previewPlaceholder.style.display = 'none';
        }
        
        // Badge preview functionality
        const badgeText = document.getElementById('badgeText');
        const badgeType = document.getElementById('badgeType');
        const badgePreview = document.getElementById('badgePreview');
        
        function updateBadgePreview() {
            const text = badgeText.value.trim();
            const type = badgeType.value;
            
            if (text && type) {
                badgePreview.textContent = text;
                badgePreview.className = 'badge-preview';
                badgePreview.classList.add('badge-' + type);
                badgePreview.style.display = 'inline-block';
            } else {
                badgePreview.style.display = 'none';
            }
        }
        
        badgeText.addEventListener('input', updateBadgePreview);
        badgeType.addEventListener('change', updateBadgePreview);
        
        // Initialize badge preview
        updateBadgePreview();
    });
</script>
@endsection
