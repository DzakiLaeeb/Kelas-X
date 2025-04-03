@extends('layouts.admin')

@section('title', 'Manage Banners')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Banners</h1>
        <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle mr-1"></i> Add New Banner
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Banners</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Link</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($banners as $banner)
                            <tr>
                                <td>{{ $banner->id }}</td>
                                <td>
                                    @if($banner->image_url)
                                        @if(Str::startsWith($banner->image_url, ['http://', 'https://']))
                                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" width="100">
                                        @else
                                            <img src="{{ asset('storage/' . $banner->image_url) }}" alt="{{ $banner->title }}" width="100">
                                        @endif
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>{{ $banner->title }}</td>
                                <td>
                                    @if($banner->link)
                                        <a href="{{ $banner->link }}" target="_blank">{{ Str::limit($banner->link, 30) }}</a>
                                    @else
                                        <span class="text-muted">No link</span>
                                    @endif
                                </td>
                                <td>{{ $banner->order ?? 0 }}</td>
                                <td>
                                    @if($banner->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ is_string($banner->created_at) ? date('d M Y, H:i', strtotime($banner->created_at)) : $banner->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-primary me-2">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No banners found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $banners->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
