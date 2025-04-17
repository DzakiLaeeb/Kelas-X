@extends('Backend.back')

@section('admincontent')
<div class="row">
    <div class="col-6">
        <form action="{{ url('admin/user/'.$user->iduser) }}" method="POST">
            @csrf


            
            <div class="mt-2">
                <label class="form-label">Password</label>
                <input class="form-control" type="password"  name="password" required>
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