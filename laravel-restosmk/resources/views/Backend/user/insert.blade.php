@extends('Backend.back')

@section('admincontent')
<div class="row">
    <div class="col-6">
        <form action="{{ url('admin/user') }}" method="POST">
            @csrf
\
            
            <div class="mt-2">
s                <label class="form-label">Level</label>
                <select class="form-control" name="level" id="">
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                    <option value="manager">Manager</option>
                </select>
                <span>
                    @error('level')
                        {{ $message }}
                    @enderror
                </span>
            </div>
            
            <div class="mt-2">
s                <label class="form-label">Nama</label>
                <input class="form-control" value="{{ old('name') }}" type="text"  name="name" required>
                <span>
                    @error('name')
                        {{ $message }}
                    @enderror
                </span>
            </div>
            
            <div class="mt-2">
                <label class="form-label">Email</label>
                <input class="form-control" value="{{ old('email') }}" type="email"  name="email" required>
                <span>
                    @error('email')
                        {{ $message }}
                    @enderror
                </span>
            </div>
                        
            <div class="mt-2">
s                <label class="form-label">Password</label>
                <input class="form-control" value="{{ old('password') }}" type="password"  name="password" required>
                <span>
                    @error('password')
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