@extends('layouts.admin')

@section('content')
<div class="admin-container">
    <h2 class="admin-heading">Daftar Pelanggan</h2>

    <div class="card admin-card">
        <div class="card-body">
            <table class="table table-admin">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Jenis Kelamin</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggans as $pelanggan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pelanggan->pelanggan }}</td>
                        <td>{{ $pelanggan->email }}</td>
                        <td>{{ $pelanggan->telp }}</td>
                        <td>{{ $pelanggan->alamat }}</td>
                        <td>{{ $pelanggan->jeniskelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                        <td>
                            <div class="action-btns">
                                <a href="{{ route('admin.pelanggan.edit', $pelanggan->idpelanggan) }}" class="btn btn-sm btn-admin-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.pelanggan.destroy', $pelanggan->idpelanggan) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data pelanggan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
