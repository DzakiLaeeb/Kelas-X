@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Profile</div>

                <div class="card-body">
                    <p>Email: {{ $pelanggan['email'] }}</p>
                    <p>Nama: {{ $pelanggan['nama'] ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection