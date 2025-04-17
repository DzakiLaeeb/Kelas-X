@extends('front')

@section('content')
    <div class="row">
        <div class="col-6">
            <form action="{{ url('/postlogin') }}" method="post">
                @csrf
                @if (Session::has('pesan'))
                <div class="alert alert-danger">
                    {{ Session::get('pesan') }}
                </div>
                @endif
                <div>
                    <label class="form-label" for="">Email</label>
                    <input class="form-control" value="{{ old('email') }}" type="text" name="email">
                    <span class="text-danger">@error('email')
                        {{ $message }}
                    @enderror</span>
                </div>
                <div>
                    <label class="form-label" for="">password</label>
                    <input class="form-control" type="password" name="password">
                    <span class="text-danger">@error('password')
                        {{ $message }}
                    @enderror</span>
                </div>
                <div>
                    <button class="btn btn-primary mt-2" type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection