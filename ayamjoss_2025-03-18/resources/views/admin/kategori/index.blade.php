@extends('layouts.admin')

@section('content')
<div class="admin-container">
    <h2 class="admin-heading">Daftar Kategori</h2>
    
    <div class="mb-3">
        <a href="{{ route('admin.kategori.create') }}" class="btn btn-admin-primary">
            <i class="fas fa-plus me-2"></i>Tambah Kategori
        </a>
    </div>

    <div class="card admin-card">
        <div class="card-body">
            <table class="table table-admin">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategoris as $kategori)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kategori->nama }}</td>
                        <td>{{ $kategori->slug }}</td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="btn btn-sm btn-admin-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection