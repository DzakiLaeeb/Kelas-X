@extends('Backend.back')

@section('admincontent')
    <div>
        <h1>User</h1>
    </div>
    <div>
        <a href="{{ url('admin/user/create') }}" class="btn btn-primary"> Tambah Data</a>
        @if (session()->has('pesan'))
            <div class="alert alert-danger mt-2">
                {{ session('pesan') }}
            </div>
        @endif
    </div>
    <div>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Level</th>
                    <th>Ubah</th>
                    <th>Hapus</th>
                </tr>
            </thead>
            @php
                $no=1;
            @endphp
            <tbody>
                @foreach ($users as $user)

                        <tr>
                            <td>{{ $no++ }}</td>
                            <td>{{ $user->user }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->level }}</td>
                            <td><a href="{{ url('admin/user/edit/'.$user->iduser) }}" class="btn btn-primary">Ubah password</a></td>
                            <td><a href="{{ url('admin/user/delete/'.$user->iduser) }}" class="btn btn-danger">Hapus</a></td>
                            
                        </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $users->withQueryString()->links() }}
    </div>
@endsection