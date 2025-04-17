@extends('Backend.back')

@section('admincontent')
<div class="row">
    <div class="col-6">
        <form action="{{ url('admin/menu') }}" method="POST">
            @csrf

            <div class="mt-2">
                <label class="form-label">Menu</label>
                <input class="form-control" type="file" name="gambar" id="gambar">
                <span>
                    @error('gambar')
                        {{ $message }}
                    @enderror
                </span>
            </div>

            <div class="mt-2">
                <label class="form-label">Deskripsi</label>
                <input class="form-control" type="file" name="deskripsi" id="deskripsi">
                <span>
                    @error('deskripsi')
                        {{ $message }}
                    @enderror
                </span>
            </div>

            <div class="mt-2">
                <label class="form-label">Harga</label>
                <input class="form-control" type="file" name="harga" id="harga">
                <span>
                    @error('harga')
                        {{ $message }}
                    @enderror
                </span>
            </div>

            <div class="mt-2">
                <label class="form-label">Gambar</label>
                <input class="form-control" type="file" name="gambar" id="gambar">
                <span>
                    @error('gambar')
                        {{ $message }}
                    @enderror
                </span>
            </div>
                        
            <div class="mt-4">
                <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
    </div>
</div>
@endsection