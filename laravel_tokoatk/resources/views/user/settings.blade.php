@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random&size=128" 
                             alt="{{ auth()->user()->name }}" class="rounded-circle img-fluid" style="width: 128px; height: 128px;">
                    </div>
                    <h4 class="mb-1">{{ auth()->user()->name }}</h4>
                    <p class="text-muted mb-3">{{ auth()->user()->email }}</p>
                    <p class="mb-0">
                        <span class="badge bg-primary">{{ auth()->user()->is_admin ? 'Admin' : 'Pelanggan' }}</span>
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-user me-2"></i> Profil Saya
                        </a>
                        <a href="{{ route('orders') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-shopping-bag me-2"></i> Pesanan Saya
                        </a>
                        <a href="{{ route('settings') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-cog me-2"></i> Pengaturan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0">Ubah Password</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('settings.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Password minimal 8 karakter.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-transparent border-0 py-3">
                    <h5 class="mb-0">Preferensi Notifikasi</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="emailNotif" checked>
                            <label class="form-check-label" for="emailNotif">Terima notifikasi email untuk pembaruan pesanan</label>
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="promoNotif" checked>
                            <label class="form-check-label" for="promoNotif">Terima email promosi dan penawaran khusus</label>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="button" class="btn btn-primary" disabled>
                                <i class="fas fa-save me-2"></i> Simpan Preferensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
