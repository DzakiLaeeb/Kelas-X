@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <div class="mb-8">
            <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-4">Thank You for Your Order!</h1>
        <p class="text-gray-600 mb-8">Your order has been successfully placed.</p>

        <div class="bg-gray-50 rounded-lg p-6 mb-8">
            <p class="text-lg font-semibold mb-2">Order ID: {{ $orderId }}</p>
            <p class="text-xl font-bold">Total: Rp {{ number_format($total, 0, ',', '.') }}</p>
        </div>

        <div class="space-y-4">
            <a href="{{ route('home') }}" 
               class="inline-block bg-blue-500 text-white px-6 py-3 rounded-md hover:bg-blue-600">
                Continue Shopping
            </a>
            <a href="{{ route('orders') }}" 
               class="block text-blue-500 hover:text-blue-700">
                View Order History
            </a>
        </div>
    </div>
</div>
@endsection