@extends('layouts.admin')

@section('content')
<div class="admin-container">
    <div class="card admin-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Tambah Kategori Baru</h3>
                <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="nama" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('nama') is-invalid @enderror" 
                           id="nama" 
                           name="nama" 
                           value="{{ old('nama') }}" 
                           placeholder="Masukkan nama kategori"
                           required>
                    @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control @error('slug') is-invalid @enderror" 
                           id="slug" 
                           name="slug" 
                           value="{{ old('slug') }}" 
                           placeholder="Masukkan slug kategori"
                           required>
                    @error('slug')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                    <small class="text-muted">Slug akan otomatis dibuat dari nama kategori</small>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Simpan
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
    const namaInput = document.getElementById('nama');
    const slugInput = document.getElementById('slug');

    namaInput.addEventListener('input', function() {
        // Create slug from nama
        let slug = this.value.toLowerCase()
            .replace(/[^\w\s-]/g, '') // Remove special characters
            .replace(/\s+/g, '-')     // Replace spaces with -
            .replace(/-+/g, '-');     // Replace multiple - with single -
        
        slugInput.value = slug;
    });
});
</script>
@endsection

@section('styles')
<style>
.admin-container {
    padding: 20px;
}

.admin-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.admin-card .card-header {
    background-color: #fff;
    border-bottom: 1px solid #eee;
    padding: 20px;
}

.admin-card .card-body {
    padding: 30px;
}

.form-label {
    font-weight: 500;
    margin-bottom: 8px;
}

.form-control {
    padding: 10px 15px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.form-control:focus {
    border-color: #e63946;
    box-shadow: 0 0 0 0.2rem rgba(230, 57, 70, 0.25);
}

.btn {
    padding: 8px 20px;
    border-radius: 8px;
    font-weight: 500;
}

.btn-primary {
    background-color: #e63946;
    border-color: #e63946;
}

.btn-primary:hover {
    background-color: #d52b38;
    border-color: #d52b38;
}

.text-danger {
    color: #e63946 !important;
}
</style>
@endsection