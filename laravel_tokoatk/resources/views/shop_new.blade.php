@extends('layouts.app')

@section('title', 'Shop')

@php
use Illuminate\Support\Str;
@endphp

@push('styles')
<style>
    /* Shop specific styles */

    /* Shop specific styles */
    body {
        background-color: var(--bg-main);
        color: var(--text-primary);
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .container {
        background-color: var(--bg-main);
    }

    h1, h2, h3, h4, h5, h6 {
        color: var(--text-primary);
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    .card {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        box-shadow: var(--card-shadow);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transform: translateY(-5px);
    }

    [data-theme="dark"] .card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
    }

    .card-title {
        color: var(--text-primary);
    }

    .card-text {
        color: var(--text-secondary);
    }

    .price {
        color: var(--primary-color);
        font-weight: bold;
    }

    .stock.text-success {
        color: var(--success-color) !important;
    }

    .stock.text-danger {
        color: var(--danger-color) !important;
    }

    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background-color: var(--primary-color);
        color: white;
    }

    .btn-secondary {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }

    .form-select {
        background-color: var(--bg-card);
        color: var(--text-primary);
        border-color: var(--border-color);
    }

    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
    }

    [data-theme="dark"] .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ecf0f1' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    }

    .empty-state {
        color: var(--text-muted);
    }

    /* Toast Notification */
    .toast-notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        transform: translateY(-20px);
        opacity: 0;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .toast-notification.show {
        transform: translateY(0);
        opacity: 1;
    }

    .toast-notification.success {
        background-color: var(--success-color, #28a745);
    }

    .toast-notification.error {
        background-color: var(--danger-color, #dc3545);
    }

    /* Pagination Styles */
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        justify-content: center;
        flex-wrap: wrap;
    }

    .pagination li {
        margin: 0 3px;
        display: inline-flex;
    }

    .pagination li a, .pagination li span {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 10px;
        text-decoration: none;
        color: var(--primary-color);
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 4px;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 500;
    }

    .pagination li.active span {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        box-shadow: var(--btn-shadow);
    }

    .pagination li a:hover {
        background-color: var(--bg-secondary);
        border-color: var(--primary-color);
        box-shadow: 0 2px 5px rgba(52, 152, 219, 0.1);
    }

    .pagination li.disabled span {
        color: var(--text-secondary);
        pointer-events: none;
        background-color: var(--bg-card);
        opacity: 0.6;
    }

    /* Fix for pagination on mobile */
    @media (max-width: 576px) {
        .pagination li a, .pagination li span {
            min-width: 35px;
            height: 35px;
            font-size: 13px;
        }

        .pagination li:not(.active):not(:first-child):not(:last-child):not(.disabled) {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<main class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">Our Products</h1>
            <p class="text-muted">Discover our wide range of high-quality products.</p>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <p class="mb-0">Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() ?? 0 }} products</p>
        </div>
        <div>
            <select class="form-select" id="sortProducts">
                <option value="newest">Newest First</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
                <option value="name_asc">Name: A to Z</option>
                <option value="name_desc">Name: Z to A</option>
            </select>
        </div>
    </div>

    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
        @if (count($products) > 0)
            @foreach ($products as $product)
                <div class="col">
                    <div class="card h-100 product-card">
                        @php
                            $imageSrc = asset('images/placeholder.png');
                            if ($product->gambar) {
                                if (filter_var($product->gambar, FILTER_VALIDATE_URL)) {
                                    $imageSrc = $product->gambar;
                                } else {
                                    $imageSrc = asset('storage/' . $product->gambar);
                                }
                            }
                        @endphp

                        <img src="{{ $imageSrc }}"
                             class="card-img-top"
                             alt="{{ $product->name }}"
                             style="height: 200px; object-fit: cover;"
                             onerror="this.src='{{ asset('images/placeholder.png') }}'">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted small">{{ $product->category_name }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                <span class="stock {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </div>
                            <div class="d-grid gap-2 mt-3">
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                                @if($product->stock > 0)
                                    <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary btn-sm w-100" disabled>Out of Stock</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h3>No products found</h3>
                    <p class="text-muted">We couldn't find any products matching your search criteria.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary mt-3">View All Products</a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="mt-4 mb-5">
        {{ $products->onEachSide(1)->links('pagination.simple') }}
    </div>

    <!-- Pagination Info -->
    <div class="text-center mb-5 text-muted">
        <small>Menampilkan {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} dari {{ $products->total() ?? 0 }} produk</small>
    </div>
</main>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle product sorting
        $('#sortProducts').change(function() {
            const sortValue = $(this).val();
            let url = new URL(window.location.href);

            // Add or update the sort parameter
            url.searchParams.set('sort', sortValue);

            // Redirect to the new URL
            window.location.href = url.toString();
        });

        // Set the selected option based on the current URL
        const urlParams = new URLSearchParams(window.location.search);
        const sortParam = urlParams.get('sort');
        if (sortParam) {
            $('#sortProducts').val(sortParam);
        }

        // Flying cart animation
        $('.add-to-cart-form').submit(function(e) {
            e.preventDefault();
            const form = $(this);
            const productCard = form.closest('.product-card');
            const productImage = productCard.find('img').eq(0);
            const cartIcon = $('.cart-icon').first();

            if (productImage.length && cartIcon.length) {
                // Create a clone of the product image for the animation
                const imgClone = productImage.clone()
                    .offset({
                        top: productImage.offset().top,
                        left: productImage.offset().left
                    })
                    .css({
                        'opacity': '0.8',
                        'position': 'absolute',
                        'height': productImage.height(),
                        'width': productImage.width(),
                        'z-index': '9999',
                        'border-radius': '10px',
                        'box-shadow': '0 5px 15px rgba(0,0,0,0.2)'
                    })
                    .appendTo($('body'))
                    .animate({
                        'top': cartIcon.offset().top,
                        'left': cartIcon.offset().left,
                        'width': 30,
                        'height': 30,
                        'opacity': 0.2,
                        'border-radius': '50%'
                    }, 800, 'easeInOutExpo');

                // Remove the clone after animation completes
                setTimeout(function() {
                    imgClone.remove();

                    // Submit the form via AJAX after animation
                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        success: function(response) {
                            if (response.success) {
                                // Update cart count
                                $('.cart-count').text(response.cart_count);

                                // Show success message
                                const toast = $('<div class="toast-notification success">'+response.message+'</div>');
                                $('body').append(toast);
                                setTimeout(function() {
                                    toast.addClass('show');
                                    setTimeout(function() {
                                        toast.removeClass('show');
                                        setTimeout(function() {
                                            toast.remove();
                                        }, 300);
                                    }, 3000);
                                }, 100);
                            }
                        },
                        error: function() {
                            // Show error message
                            const toast = $('<div class="toast-notification error">Failed to add product to cart</div>');
                            $('body').append(toast);
                            setTimeout(function() {
                                toast.addClass('show');
                                setTimeout(function() {
                                    toast.removeClass('show');
                                    setTimeout(function() {
                                        toast.remove();
                                    }, 300);
                                }, 3000);
                            }, 100);
                        }
                    });
                }, 800);
            } else {
                // If animation can't be performed, just submit the form
                form.unbind('submit').submit();
            }
        });
    });
</script>
@endpush
