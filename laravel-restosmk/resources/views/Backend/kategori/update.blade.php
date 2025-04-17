@extends('Backend.back')

@section('admincontent')
<div class="row">
    <div class="col-6">
        <form action="{{ url('admin/kategori') }}" method="POST">
            @csrf


            
            <div class="mt-2">
                <label class="form-label">Kategori</label>
                <input class="form-control" value="{{ $kategori->kategori }}" name="kategori" type="text" required>
                <span>
                    @error('kategori')
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