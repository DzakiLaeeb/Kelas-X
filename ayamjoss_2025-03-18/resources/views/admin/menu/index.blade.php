
@extends('layouts.admin')

@section('content')
<style>
    .menu-table-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin: 20px 0;
    }
    
    .menu-table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    .menu-table thead {
        background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
        color: white;
    }
    
    .menu-table th {
        padding: 15px;
        text-align: left;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .menu-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        font-size: 14px;
        vertical-align: middle;
    }
    
    .menu-table tbody tr:hover {
        background-color: #f8f9ff;
    }
    
    .menu-table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .menu-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .menu-img:hover {
        transform: scale(1.1);
    }
    
    .menu-price {
        font-weight: bold;
        color: #ef4444;
    }
    /* Gaya dasar untuk semua badge menu */
    .menu-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        line-height: 1;
        border-radius: 0.375rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .menu-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Gaya untuk tipe badge yang berbeda */
    .badge-primary {
        background: linear-gradient(135deg, #4e73df, #224abe);
        color: white;
    }

    .badge-success {
        background: linear-gradient(135deg, #1cc88a, #13855c);
        color: white;
    }

    .badge-info {
        background: linear-gradient(135deg, #36b9cc, #258391);
        color: white;
    }

    .badge-warning {
        background: linear-gradient(135deg, #f6c23e, #dda20a);
        color: #6e5319;
    }

    .badge-danger {
        background: linear-gradient(135deg, #e74a3b, #be2617);
        color: white;
    }

    .badge-secondary {
        background: linear-gradient(135deg, #858796, #60616f);
        color: white;
    }

    .badge-light {
        background: linear-gradient(135deg, #f8f9fc, #d4d9e9);
        color: #5a5c69;
        border: 1px solid #e3e6f0;
    }

    .badge-dark {
        background: linear-gradient(135deg, #5a5c69, #373840);
        color: white;
    }

    /* Animasi pulse dari Animate.css */
    .animate__animated {
        animation-duration: 1s;
        animation-fill-mode: both;
    }

    .animate__pulse {
        animation-name: pulse;
        animation-timing-function: ease-in-out;
        animation-iteration-count: infinite;
        animation-duration: 2s;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
        100% {
            transform: scale(1);
        }
    }

    
    .action-buttons {
        display: flex;
        gap: 8px;
    }
    
    .btn-edit, .btn-delete {
        border: none;
        border-radius: 4px;
        padding: 6px 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-edit {
        background-color: #f59e0b;
        color: white;
    }
    
    .btn-delete {
        background-color: #ef4444;
        color: white;
    }
    
    .btn-edit:hover, .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .empty-state {
        text-align: center;
        padding: 30px;
        color: #6b7280;
    }
    
    .btn-add-menu {
        background-color: #ef4444;
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-add-menu:hover {
        background-color: #ef4444;;
        transform: translateY(-2px);
        color: white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2>Daftar Menu</h2>
            <a href="{{ route('admin.menu.create') }}" class="btn btn-add-menu mb-3">Tambah Menu Baru</a>
            
            <div class="menu-table-container">
                <table class="menu-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Badge</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($menus as $menu)
                            <tr>
                                <td>{{ $menu->idmenu }}</td>
                                <td>
                                    <img src="{{ $menu->gambar }}" alt="{{ $menu->nama }}" class="menu-img">
                                </td>
                                <td>{{ $menu->nama }}</td>
                                <td>{{ $menu->kategori ? $menu->kategori->nama : 'Tanpa Kategori' }}</td>
                                <td class="menu-price">Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                                <td>
                                    @if($menu->badge)
                                        <span class="menu-badge badge-{{ $menu->badge_type }}">{{ $menu->badge }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.menu.edit', $menu->idmenu) }}" class="btn-edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.menu.destroy', $menu->idmenu) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-delete" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <p>Tidak ada menu yang tersedia</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection








