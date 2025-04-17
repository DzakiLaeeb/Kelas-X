@extends('front')

@section('content')
    <div class="row">
        <div class="col-6">
            <form action="{{ url('/postregister') }}" method="post">
                @csrf
                <div>
                    <label  class="form-label" for="">Pelanggan</label>
                    <input class="form-control" value="{{ old('pelanggan') }}" type="text" name="pelanggan">
                    <span class="text-danger">@error('pelanggan')
                        {{ $message }}
                    @enderror</span>
                </div>
                <div>
                    <label class="form-label" for="">Alamat</label>
                    <input class="form-control" value="{{ old('alamat') }}" type="text" name="alamat">
                    <span class="text-danger">@error('alamat')
                        {{ $message }}
                    @enderror</span>
                </div>
                <div>
                    <label class="form-label" for="telp">Telpon</label>
                    <input class="form-control" value="{{ old('telp') }}" type="tel" name="telp" id="telp">
                    <span class="text-danger">@error('telp')
                        {{ $message }}
                    @enderror</span>
                </div>
                <div>
                    <label class="form-label" for="jeniskelamin">Jenis Kelamin</label>
                    <select class="form-select" name="jeniskelamin" id="jeniskelamin">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                        <option value="non" selected>Non Binary</option>
                    </select>
                </div>
                <div>
                    <label class="form-label" for="">Email</label>
                    <input class="form-control" value="{{ old('email') }}" type="text" name="email">
                    <span class="text-danger">@error('email')
                        {{ $message }}
                    @enderror</span>
                </div>
                <div>
                    <label class="form-label" for="">Password</label>
                    <input class="form-control" type="password" name="password">
                    <span class="text-danger">@error('password')
                        {{ $message }}
                    @enderror</span>
                </div>
                <div>
                    <button class="btn btn-primary mt-2" type="submit">Register</button>
                </div>
            </form>
        </div>
    </div>
@endsection