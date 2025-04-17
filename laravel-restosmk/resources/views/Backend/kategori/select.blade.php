@extends('Backend.back')

@section('admincontent')
    <div>
        <h1>Menus</h1>
    </div>
    <div>
        <a href="{{ url('admin/kategori/create') }}" class="btn btn-primary"> Tambah Data</a>
    </div>
    <div>
        <form action="{{ url('admin/select') }}" method="get">
            @csrf
            <div class="mt-2">
                <select class="form-control" name="idkategori" onchange="this.form.submit()">
                    <option value="">--- PILIH KATEGORI ---</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->idkategori }}">{{ $kategori->kategori }}</option>
                    @endforeach
                </select>
                <span>
                    @error('kategori')
                        {{ $message }}
                    @enderror
                </span>
            </div>
            <div class="mt-4">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>
    </div>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kategori</th>
                    <th>Menuu</th>
                    <th>Deskripsi</th>
                    <th>Gambar</th>
                    <th>Harga</th>
                    <th>Ubah</th>
                    <th>Hapus</th>
                </tr>
            </thead>
            @php
                $no=1;
            @endphp
            <tbody>
                @foreach ($menus as $menu)

                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $menu->kategori }}</td>
                            <td>{{ $menu->menu }}</td>
                            <td>{{ $menu->deskripsi }}</td>
                            <td><img src="{{ asset('gambar/'.$menu->gambar) }}" width="100px" alt="Gambar {{ $menu->menu }}"></td>
                            <td>{{ $menu->harga }}</td>
                            <td><a href="{{ url('admin/kategori/edit/'.$menu->idmenu) }}" class="btn btn-primary">Ubah</a></td>
                            <td><a href="{{ url('admin/kategori/delete/'.$kategori->idkategori) }}" class="btn btn-danger">Hapus</a></td>
                            
                        </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $menus->withQueryString()->links() }}
    </div>
@endsection