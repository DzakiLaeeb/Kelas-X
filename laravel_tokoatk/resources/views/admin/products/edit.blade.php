@extends('layouts.admin')

@section('title', 'Edit Product')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Product: {{ $product->nama_produk }}</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to Products
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Product Name</label>
                    <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}" required>
                    @error('nama_produk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $product->description ?? $product->deskripsi ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id ?? 1) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="harga" class="form-label">Price (Rp)</label>
                            <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', $product->harga) }}" required>
                            @error('harga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stock</label>
                            <input type="number" class="form-control @error('stok') is-invalid @enderror" id="stok" name="stok" value="{{ old('stok', $product->stok) }}">
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Product Image</label>
                    <!-- Debug info - hidden
                    Image path: {{ $product->gambar }}
                    Is URL: {{ Str::startsWith($product->gambar ?? '', ['http://', 'https://']) ? 'Yes' : 'No' }}
                    -->

                    @if($product->gambar)
                        <div class="mb-2">
                            @if(Str::startsWith($product->gambar, ['http://', 'https://']))
                                <img src="{{ $product->gambar }}" alt="{{ $product->nama_produk }}" width="100" class="img-thumbnail">
                                <p class="text-muted small">Current image: URL</p>
                            @else
                                <img src="{{ asset('storage/' . $product->gambar) }}" alt="{{ $product->nama_produk }}" width="100" class="img-thumbnail">
                                <p class="text-muted small">Current image: Uploaded file</p>
                            @endif
                        </div>
                    @endif

                    <div class="mb-2">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="image_type" id="imageTypeFile" value="file" {{ !Str::startsWith($product->gambar ?? '', ['http://', 'https://']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="imageTypeFile">Upload File</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="image_type" id="imageTypeUrl" value="url" {{ Str::startsWith($product->gambar ?? '', ['http://', 'https://']) ? 'checked' : '' }}>
                            <label class="form-check-label" for="imageTypeUrl">Image URL</label>
                        </div>
                    </div>

                    <div id="fileUploadSection" {{ Str::startsWith($product->gambar ?? '', ['http://', 'https://']) ? 'style="display: none;"' : '' }}>
                        <input type="file" class="form-control @error('gambar') is-invalid @enderror" id="gambar" name="gambar">
                        <small class="form-text text-muted">Supported formats: JPG, JPEG, PNG, GIF. Max size: 2MB. Leave empty to keep the current image.</small>
                    </div>

                    <div id="urlInputSection" {{ !Str::startsWith($product->gambar ?? '', ['http://', 'https://']) ? 'style="display: none;"' : '' }}>
                        <input type="url" class="form-control @error('gambar_url') is-invalid @enderror" id="gambar_url" name="gambar_url" placeholder="https://example.com/image.jpg" value="{{ Str::startsWith($product->gambar ?? '', ['http://', 'https://']) ? $product->gambar : '' }}">
                        <small class="form-text text-muted">Enter a valid image URL (must start with http:// or https://). Leave empty to keep the current image.</small>
                        <div class="alert alert-warning mt-2">
                            <strong>Note:</strong> Blob URLs (starting with blob:) are not supported as they are temporary and only work in the browser that created them. Please use direct image URLs instead.
                        </div>
                        <div class="alert alert-info mt-2">
                            <strong>Example valid URLs:</strong>
                            <ul class="mb-0">
                                <li>https://via.placeholder.com/300x200</li>
                                <li>https://picsum.photos/300/200</li>
                                <li>https://images.unsplash.com/photo-1505740420928-5e560c06d30e</li>
                                <li>https://i.imgur.com/XqjPbNI.jpg</li>
                            </ul>
                            <p class="mt-2 mb-0"><strong>Try these URLs:</strong> They are guaranteed to work!</p>
                        </div>
                    </div>

                    @error('gambar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @error('gambar_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Update Product</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageTypeFile = document.getElementById('imageTypeFile');
        const imageTypeUrl = document.getElementById('imageTypeUrl');
        const fileUploadSection = document.getElementById('fileUploadSection');
        const urlInputSection = document.getElementById('urlInputSection');
        const gambarInput = document.getElementById('gambar');
        const gambarUrlInput = document.getElementById('gambar_url');

        // Function to toggle between file upload and URL input
        function toggleImageInputType() {
            if (imageTypeFile.checked) {
                fileUploadSection.style.display = 'block';
                urlInputSection.style.display = 'none';
            } else {
                fileUploadSection.style.display = 'none';
                urlInputSection.style.display = 'block';
            }
        }

        // Add event listeners
        imageTypeFile.addEventListener('change', toggleImageInputType);
        imageTypeUrl.addEventListener('change', toggleImageInputType);
    });
</script>
@endpush
