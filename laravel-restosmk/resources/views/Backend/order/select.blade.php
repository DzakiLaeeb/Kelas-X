@extends('Backend.back')

@section('admincontent')
    <div>
        <h1>Order</h1>
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
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Bayar</th>
                    <th>Kembali</th>
                    <th>Status</th>
                </tr>
            </thead>
            @php
                $no=1;
            @endphp
            <tbody>
                @foreach ($orders as $order)

                        <tr>
                            <td>{{ $no++ }}</td>
                            <td><a href="{{ url('admin/order/edit/'.$order->idorder) }}">{{ $order->pelanggan }}</a></td>
                            <td>{{ $order->tglorder }}</td>
                            <td>{{ $order->total }}</td>
                            <td>{{ $order->bayar }}</td>
                            <td>{{ $order->kembali }}</td>
                            @php
                            $status = "LUNAS";
                                if ($order->status == 0) {
                                    $status = "<a href='".url('admin/order/edit/'.$order->idorder)."'>BAYAR</a>";
                                }
                            @endphp
                            <td>{{!! $status !!}}</td>
                        </tr>

                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-5">
        {{ $orders->withQueryString()->links() }}
    </div>
@endsection